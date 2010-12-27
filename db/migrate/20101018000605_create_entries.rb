class CreateEntries < ActiveRecord::Migration
  def self.up
    create_table :entries do |t|
      t.string :uuid, :null => false
      t.string :album
      t.string :track
      t.string :artist, :null => false
      t.string :title, :null => false
      t.string :year
      t.string :genre
      t.string :path

      t.timestamps
    end
    add_index :entries, :uuid, :unique => true
  end

  def self.down
    drop_table :entries
  end
end
