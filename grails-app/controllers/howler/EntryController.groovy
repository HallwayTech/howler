package howler

import howler.Entry;

class EntryController {
//	def scaffold = Entry
	
	def findAllBy = {
		def properType = params.type[0].toUpperCase() + params.type[1..-1].toLowerCase()
		def types = Entry."findAllBy${properType}"(params."${params.type}", params)
		[entries:types]
	}
	
	def listBy = {
		params.first = params.first ? params.int('first') : null
		params.max = params.max ? params.int('max') : null
		
		def entries = Entry.withCriteria {
			cache false
			projections {
				groupProperty params.type
				rowCount()
			}
			if (params.first) {
				firstResult(params.first)
			}
			if (params.max) {
				maxResults(params.max)
			}
			order params.type
		}
		[entries:entries, type: params.type]
	}
	
	def stream = {
		def entry = Entry.get(params.id)
		def file = new File(entry.path)
		if (file.canRead()) {
			response.status = 200
			response.contentType = "audio/mpeg"
			response.setHeader "Content-Length", "${file.length()}"
			response.setHeader "Content-Disposition", "attachment; filename=${params.id}"
			response.outputStream << file.newInputStream()
			response.outputStream.flush()
		} else {
			response.sendError 404
		}
	}

/*
    static allowedMethods = [save: "POST", update: "POST", delete: "POST"]

    def index = {
        redirect(action: "list", params: params)
    }

    def list = {
        params.max = Math.min(params.max ? params.int('max') : 10, 100)
        [entryInstanceList: Entry.list(params), entryInstanceTotal: Entry.count()]
    }

    def create = {
        def entryInstance = new Entry()
        entryInstance.properties = params
        return [entryInstance: entryInstance]
    }

    def save = {
        def entryInstance = new Entry(params)
        if (entryInstance.save(flush: true)) {
            flash.message = "${message(code: 'default.created.message', args: [message(code: 'entry.label', default: 'Entry'), entryInstance.id])}"
            redirect(action: "show", id: entryInstance.id)
        }
        else {
            render(view: "create", model: [entryInstance: entryInstance])
        }
    }

    def show = {
        def entryInstance = Entry.get(params.id)
        if (!entryInstance) {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'entry.label', default: 'Entry'), params.id])}"
            redirect(action: "list")
        }
        else {
            [entryInstance: entryInstance]
        }
    }

    def edit = {
        def entryInstance = Entry.get(params.id)
        if (!entryInstance) {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'entry.label', default: 'Entry'), params.id])}"
            redirect(action: "list")
        }
        else {
            return [entryInstance: entryInstance]
        }
    }

    def update = {
        def entryInstance = Entry.get(params.id)
        if (entryInstance) {
            if (params.version) {
                def version = params.version.toLong()
                if (entryInstance.version > version) {
                    
                    entryInstance.errors.rejectValue("version", "default.optimistic.locking.failure", [message(code: 'entry.label', default: 'Entry')] as Object[], "Another user has updated this Entry while you were editing")
                    render(view: "edit", model: [entryInstance: entryInstance])
                    return
                }
            }
            entryInstance.properties = params
            if (!entryInstance.hasErrors() && entryInstance.save(flush: true)) {
                flash.message = "${message(code: 'default.updated.message', args: [message(code: 'entry.label', default: 'Entry'), entryInstance.id])}"
                redirect(action: "show", id: entryInstance.id)
            }
            else {
                render(view: "edit", model: [entryInstance: entryInstance])
            }
        }
        else {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'entry.label', default: 'Entry'), params.id])}"
            redirect(action: "list")
        }
    }

    def delete = {
        def entryInstance = Entry.get(params.id)
        if (entryInstance) {
            try {
                entryInstance.delete(flush: true)
                flash.message = "${message(code: 'default.deleted.message', args: [message(code: 'entry.label', default: 'Entry'), params.id])}"
                redirect(action: "list")
            }
            catch (org.springframework.dao.DataIntegrityViolationException e) {
                flash.message = "${message(code: 'default.not.deleted.message', args: [message(code: 'entry.label', default: 'Entry'), params.id])}"
                redirect(action: "show", id: params.id)
            }
        }
        else {
            flash.message = "${message(code: 'default.not.found.message', args: [message(code: 'entry.label', default: 'Entry'), params.id])}"
            redirect(action: "list")
        }
    }
    */
}
