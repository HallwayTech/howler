class EntryController < ApplicationController
  # Find entries by a field.
  def find_all_by
    @entries = Entry.where("#{params[:type]} = ?", params[params[:type]]).order('album, track, path')
    render(:find_all_by, :layout => false)
  end

  # List all entries by a certain type (e.g. artist, album).
  def list_by
    @type = params[:type]
    @entries = Entry.count(:group => params[:type], :order => params[:type])
    render(:list_by, :layout => false)
  end

  def stream
    begin
      entry = Entry.find(params[:id])
      send_file(entry.path, :type => 'audio/mpeg', :filename => params[:id])
    rescue RecordNotFound, MissingFile
      render(:status => 404)
    end
  end
end
