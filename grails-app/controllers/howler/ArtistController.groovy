package howler

class ArtistController {
	def defaultAction = 'list'

	def list = {
        params.max = Math.min(params.max ? params.int('max') : 10, 100)
		def entries = Entry.withCriteria {
			cache false
			projections {
				groupProperty 'artist'
				rowCount()
			}
			order 'artist'
		}
		render(view: '../entry/list', model: [entries:entries])
	}
}
