class EntryController < ApplicationController
  # Find entries by a field.
  def find_by
    # remove known parameters that we don't need
    filtered_params = params.reject { |key, value| ['controller', 'action'].include?(key) }

    # make sure there were some submitted params. if not send 400 and return
    render(status: 400) and return if filtered_params.blank?

    # search for the requested information
    @entries = Entry.where(filtered_params).order('album, track, path')

    # render the results
    render(:find_by, layout: false)
  end

  # List all entries by a certain type (e.g. artist, album).
  def list_by
    # store the type so the template can use it
    @type = params[:type]

    # look up the entries requested
    @entries = Entry.count(group: @type, order: @type)

    # render the results
    render(:list_by, layout: false)
  end

  # Stream a file associated with a database entry to the response.
  def stream
    begin
      # lookup the entry requested
      entry = Entry.find(params[:id])

      # send the associated file directly to the response
      send_file(entry.path, type: 'audio/mpeg', filename: params[:id])

    rescue RecordNotFound, MissingFile
      # if record or file missing, return 404
      render(status: 404)
    end
  end
end
