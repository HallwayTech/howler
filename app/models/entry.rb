class Entry < ActiveRecord::Base
    attr_protected :uuid
    before_validation_on_create :init_uuid
    validates_presence_of :uuid
    validates_uniqueness_of :uuid
    
    private
    def init_uuid
        self.uuid = UUIDTools::UUID.timestamp_create.to_s
    end
end
