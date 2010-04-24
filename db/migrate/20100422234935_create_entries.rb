class CreateEntries < ActiveRecord::Migration
  def self.up
    create_table :entries do |t|
      t.string :artist
      t.string :album
      t.string :title

      t.timestamps
    end
  end

  def self.down
    drop_table :entries
  end
end
