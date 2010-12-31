# requirements for rails
require 'rubygems'
require 'yaml'
require 'active_record'
require 'logger'

# requirements for model
#PROJECT_HOME = "#{ENV['HOME']}/projects/howler/"
PROJECT_HOME = File.expand_path("#{$0}/../..")
require "#{PROJECT_HOME}/app/models/entry.rb"

# local requirements for processing
require 'find'
require 'mp3info'
require 'iconv'

INITIAL_LOAD = false

# setup for using the rails model
ActiveRecord::Base.logger = Logger.new(STDERR)
db_config = YAML::load(File.open("#{PROJECT_HOME}/config/database.yml"))
ActiveRecord::Base.establish_connection(db_config['production'])

c = Iconv.new('UTF-8//IGNORE', 'UTF-8')

path = ARGV[0]
ActiveRecord::Base.transaction do
  begin
    Find.find(path) do |entry|
      entry = c.iconv entry
      Mp3Info.open(entry, :encoding => 'utf-8') do |id3|
          t = id3.tag

          if INITIAL_LOAD
            e = Entry.new(:path => entry)
          else
            e = Entry.find_or_initialize_by_path(entry)
          end
          e.artist = c.iconv(t.artist)
          e.album = c.iconv(t.album)
          e.title = c.iconv(t.title)
          e.track = t.tracknum
          e.genre = t.genre_s
          e.year = t.year
          e.save!
        end if File.file?(entry) and entry[/.+\.mp3$/]
    end

  rescue Exception
    puts $!, $@
    raise ActiveRecord::Rollback
  end
end
