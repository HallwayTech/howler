package howler

import howler.Entry;

class ArtistController {
	def defaultAction = 'list'
	
	def list = {
		params.max = params.max ? params.int('max') : null
		def entries = Entry.withCriteria {
			cache false
			projections {
				groupProperty 'artist'
				rowCount()
			}
			if (params.max) {
				maxResults(params.max)
			}
			order 'artist'
		}
		render(view: '../entry/list', model: [entries:entries])
	}
}
