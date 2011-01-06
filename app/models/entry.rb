#require 'uuidtools'

class Entry < ActiveRecord::Base
  #attr_protected :uuid
  #before_validation :init_uuid, on: :create
  #validates_presence_of :uuid
  #validates_uniqueness_of :uuid

  #set_primary_key :uuid

  #private
  #def init_uuid
  #  self.uuid = UUIDTools::UUID.timestamp_create.to_s
  #end
end
