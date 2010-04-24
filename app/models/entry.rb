class Entry < ActiveRecord::Base
#  validates_numericality_of :track
  validates_presence_of :artist, :title
end
