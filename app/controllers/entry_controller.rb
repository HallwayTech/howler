class EntryController < ApplicationController
    # Find entries by a field.
    def find_all_by
        @entries = Entry.where("#{params[:type]} = ?", params[params[:type]]).order('album, track, artist')
        render(:find_all_by, :layout => false)
    end

    # List all entries by a certain type (e.g. artist, album).
    def list_by
        @type = params[:type]
        @entries = Entry.count(:all, :group => params[:type], :order => params[:type])
        render(:list_by, :layout => false)
    end

    # def stream
    #     entry = Entry.get(params[:id])
    #     file = new File(entry.path)
    #     if (file.canRead())
    #         response.status = 200
    #         response.contentType = "audio/mpeg"
    #         response.setHeader "Content-Length", "#{file.length()}"
    #         response.setHeader "Content-Disposition", "attachment; filename=#{params.id}"
    #         def os = response.outputStream
    #         os << file.newInputStream()
    #         os.flush()
    #     else
    #         response.sendError 404
    #     end
    # end
    # 
    # def category(name)
    #     categoryMatch = name =~ /(A-Za-z0-9)/
    #     category = ''
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
