class EntryController < ApplicationController
    def find_all_by
        ## TODO convert this from grails to proper rails
        def properType = params.type[0].toUpperCase() + params.type[1..-1].toLowerCase()
        properType = params[:type].capitalize

        @types = Entry.where({ params[:type] => params[params[:type]] }).order('album, track, artist')
#		def types = Entry."findAllBy${properType}"(params."${params.type}", params)
#        [entries:types]
    end

end
