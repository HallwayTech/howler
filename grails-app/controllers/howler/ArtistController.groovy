package howler

class ArtistController {
	def index = {
		[
			id: '1',
			label: 'Nah ah!',
			parent: 'Mo Ron',
			dirs: [
		        [id: '1', label: 'First Folder, Yeah!'],
		        [id: '2', label: "Freakin' Rock"]
		    ],
		    files: [
		        [id: '1', label: 'This song sucks'],
		        [id: '2', label: 'Yo Momma!']
		    ]
        ]
	}
}
