# This file should contain all the record creation needed to seed the database with its default values.
# The data can then be loaded with the rake db:seed (or created alongside the db with db:setup).
#
# Examples:
#
#   cities = City.create([{ :name => 'Chicago' }, { :name => 'Copenhagen' }])
#   Mayor.create(:name => 'Daley', :city => cities.first)
entries = Entry.create([
    {:artist => 'Mo Ron', :album => 'Nah ah!', :title => 'This song sucks', :path => '/Mo Ron/Nah ah!/This song sucks.ogg'},
    {:artist => 'Mo Ron', :album => 'Nah ah!', :title => 'This song sucks', :path => '/Mo Ron/Nah ah!/This song sucks.ogg'},
    {:artist => 'Mo Ron', :album => 'Nah ah!', :title => 'Yo Momma!', :path => '/Mo Ron/Nah ah!/Yo Momma!.ogg'},
    {:artist => 'Johnny Rocket', :album => 'To The Moon', :title => 'Shippity Do Dah', :path => '/Johnny Rocket/To The Moon/Shippity Doo Dah.ogg'},
    {:artist => 'Tommy Bells', :album => 'Ringer', :title => 'Cling Clang', :path => '/Tommy Bells/Ringer/Cling Clang.ogg'},
    {:artist => 'Johnny Knocks', :album => 'Ringer', :title => 'Cling Clang', :path => '/Johnny Knocks/Ringer/Cling Clang.ogg'}
])
