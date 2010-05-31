import howler.Entry;

class BootStrap {

     def init = { servletContext ->
		 new Entry(artist: 'Mo Ron', album: 'Nah ah!', title: 'This song sucks').save()
		 new Entry(artist: 'Mo Ron', album: 'Nah ah!', title: 'Yo Momma!').save()
     }

     def destroy = {
     }
}