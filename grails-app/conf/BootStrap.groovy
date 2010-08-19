import howler.Entry

class BootStrap {
	
	def init = { servletContext ->
		environments {
			development {
				new Entry(artist: 'Mo Ron', album: 'Nah ah!', title: 'This song sucks',
					path: '/Mo Ron/Nah ah!/This song sucks.ogg').save(failOnError:true)
				new Entry(artist: 'Mo Ron', album: 'Nah ah!', title: 'Yo Momma!',
					path: '/Mo Ron/Nah ah!/Yo Momma!.ogg').save(failOnError:true)
				new Entry(artist: 'Johnny Rocket', album: 'To The Moon', title: 'Shippity Do Dah',
					path: '/Johnny Rocket/To The Moon/Shippity Doo Dah.ogg').save(failOnError:true)
				new Entry(artist: 'Tommy Bells', album: 'Ringer', title: 'Cling Clang',
					path: '/Tommy Bells/Ringer/Cling Clang.ogg').save(failOnError:true)
				new Entry(artist: 'Johnny Knocks', album: 'Ringer', title: 'Cling Clang',
					path: '/Johnny Knocks/Ringer/Cling Clang.ogg').save(failOnError:true)
			}
		}
	}
	
	def destroy = {
	}
}