# requirements for rails
require 'rubygems'
require 'yaml'
require 'active_record'
require 'logger'

# requirements for model
PROJECT_HOME = File.expand_path("#{$0}/../..")
require "#{PROJECT_HOME}/app/models/entry.rb"

# local requirements for processing
require 'find'
require 'mp3info'
require 'iconv'

## whether to just insert data or check for matches by path
INITIAL_LOAD = true

# setup for using the rails model
ActiveRecord::Base.logger = Logger.new(STDERR)
db_config = YAML::load(File.open("#{PROJECT_HOME}/config/database.yml"))
ActiveRecord::Base.establish_connection(db_config['production'])

# create a logger
log = Logger.new('unprocessed.log')
# use iconv to convert to utf8
ic = Iconv.new('UTF-8//IGNORE', 'UTF-8')
# counters for stats
processed = 0
unprocessed = 0

path = ARGV[0]
ActiveRecord::Base.transaction do
  begin
    Find.find(path) do |entry|
      entry = ic.iconv(entry + ' ')[0..-2]
      Mp3Info.open(entry, :encoding => 'utf-8') do |id3|
          tag = id3.tag

          log.warn("#{entry}; artist = #{tag.artist}, album = #{tag.album}, title = #{tag.title}") and unprocessed += 1 and break if tag.artist.nil? or tag.title.nil?

          if INITIAL_LOAD
            e = Entry.new(:path => entry)
          else
            e = Entry.find_or_initialize_by_path(entry)
          end
          e.artist = ic.iconv(tag.artist.strip)
          e.album = ic.iconv(tag.album.strip) unless tag.album.nil?
          e.title = ic.iconv(tag.title.strip)
          e.track = tag.tracknum
          e.genre = tag.genre_s.strip unless tag.genre_s.nil?
          e.year = tag.year
          e.save!
		  processed += 1
        end if File.file?(entry) and entry =~ /.+\.mp3$/
    end

  rescue
    puts $!, $@
    raise ActiveRecord::Rollback
  end
end

log.info("processed: #{processed}, unprocessed: #{unprocessed}\n")

