class EntryController < ApplicationController
    # Find entries by a field.
    def find_all_by
        @entries = Entry.where({ params[:type] => params[params[:type]] }).order('album, track, artist')
        # def properType = params.type[0].toUpperCase() + params.type[1..-1].toLowerCase()
        # 
        # def types = Entry.withCriteria {
        #     cache true
        #     eq params.type, params."${params.type}"
        #     order 'album'
        #     order 'track'
        #     order 'artist'
        # }
    end

    # List all entries by a certain type (e.g. artist, album).
    def list_by
        # params.first = params.first ? params.int('first') : null
        # params.max = params.max ? params.int('max') : null

        @type = params[:type]
        @entries = Entry.count(:all, :conditions => "#{params[:type]} = #{params[params[:type]]}", :order => params[:type])
        # def entries = Entry.withCriteria {
        #     cache true
        #     projections {
        #         groupProperty params.type
        #         rowCount()
        #     }
            # if (params.first)
            #     firstResult(params.first)
            # end
            # if (params.max)
            #     maxResults(params.max)
            # end
        #     order params.type
        # }
        # [entries: entries, type: params.type]
    end

    # def stream
    #     def entry = Entry.get(params[:id])
    #     def file = new File(entry.path)
    #     if (file.canRead())
    #         response.status = 200
    #         response.contentType = "audio/mpeg"
    #         response.setHeader "Content-Length", "${file.length()}"
    #         response.setHeader "Content-Disposition", "attachment; filename=${params.id}"
    #         def os = response.outputStream
    #         os << file.newInputStream()
    #         os.flush()
    #     else
    #         response.sendError 404
    #     end
    # end
    # 
    # def category(name)
    #     def categoryMatch = name =~ /(A-Za-z0-9)/
    #     def category = ''
    #     (name =~ /([A-Za-z0-9])/).each do |match, group|
    #         if (category == '') {
    #             if (group.isNumber())
    #                 category = '#'
    #             else
    #                 category = group.toUpperCase()
    #             end
    #         end
    #     end
    #     return category
    # end
end
