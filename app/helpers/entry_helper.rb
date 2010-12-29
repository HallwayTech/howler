module EntryHelper
  # Renders JavaScript to have jQuery call to the server.
  #
  # *controller*:: Controller to call to. See +url_for+.
  # *action*:: Action to call to. See +url_for+.
  # params:: Parameters (data) to send with the call.
  # update:: HTML entity to update. Accepts a jQuery selector.
  def remote_function(var)
    url = url_for(:controller => var[:controller], :action => var[:action])
    js = "$.get('#{url}'"
    js += ", #{ActiveSupport::JSON.encode(var[:params])}" if var[:params]
    js += ", function(data) {$('#{var[:update]}').html(data)}" if var[:update]
    js += ');return false'
    return js
  end

  # Renders an anchor link that makes a remote call using AJAX. See
  # +remote_function+.
  def link_to_remote(var)
    onclick = remote_function(var)
    #link = "<a href='#' onclick='#{onclick}' title='#{var[:title]}'>${artist}</a>
  end
end
