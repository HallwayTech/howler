package howler

import howler.Entry;

class AlbumController {
	def defaultAction = 'list'
	
	def list = {
		params.first = params.first ? params.int('first') : null
		params.max = params.max ? params.int('max') : null
		def entries = Entry.withCriteria {
			cache false
			projections {
				groupProperty 'album'
				rowCount()
			}
			if (params.first) {
				firstResult(params.first)
			}
			if (params.max) {
				maxResults(params.max)
			}
			order 'album'
		}
		render(view: '../entry/list', model: [entries:entries, type:'Album'])
	}
}
