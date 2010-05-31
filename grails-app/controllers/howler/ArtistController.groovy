package howler

class ArtistController {
	def list = {
        params.max = Math.min(params.max ? params.int('max') : 10, 100)
		def testEntries = [
			[
			 	id: 1,
				artist:'Mo Ron',
				album:'Something',
				title:'Yo Momma'
		   ]
		]
//		entries = Entry.list(params)
//		entries = testEntries
		def crit = Entry.createCriteria()
		def results = crit.list {
        	eq('artist', params.artist)
			projections {
				groupProperty('artist')
				count('artist')
			}
			order('artist')
		}
		[entries:testEntries, entriesCount:Entry.count()]
	}
}
