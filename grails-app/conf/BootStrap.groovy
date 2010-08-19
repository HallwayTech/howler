
class BootStrap {

     def init = { servletContext ->
		 environments {
			 development {
//				 new Entry(artist: 'Mo Ron', album: 'Nah ah!', title: 'This song sucks').save(failOnError:true)
//				 new Entry(artist: 'Mo Ron', album: 'Nah ah!', title: 'Yo Momma!').save(failOnError:true)
//				 new Entry(artist: 'Johnny Rocket', album: 'To The Moon', title: 'Shippity Do Dah').save(failOnError:true)
//				 new Entry(artist: 'Tommy Bells', album: 'Ringer', title: 'Cling Clang').save(failOnError:true)
//				 new Entry(artist: 'Johnny Knocks', album: 'Ringer', title: 'Cling Clang').save(failOnError:true)
			 }
		 }
     }

     def destroy = {
     }
}