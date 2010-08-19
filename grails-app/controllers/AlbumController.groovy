
class AlbumController {
	def defaultAction = 'list'

	def list = {
    params.max = Math.min(params.max ? params.int('max') : 10, 100)
		def entries = Entry.withCriteria {
			cache false
			projections {
				groupProperty 'album'
				rowCount()
			}
      maxResults(params.max)
			order 'album'
		}
		render(view: '../entry/list', model: [entries: entries])
	}
}
