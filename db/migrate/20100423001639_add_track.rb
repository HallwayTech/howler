class AddTrack < ActiveRecord::Migration
  def self.up
    add_column :entries, :track, :string
  end

  def self.down
    remove_column :entries, :track
  end
end
