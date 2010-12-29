module EntryHelper
  # Renders JavaScript to have jQuery call to the server.
  #
  # *controller*:: Controller to call to. See +url_for+.
  # *action*:: Action to call to. See +url_for+.
  # params:: Parameters (data) to send with the call.
  # update:: HTML entity to update. Accepts a jQuery selector.
  def remote_function(args)
    url = url_for(:controller => args[:controller], :action => args[:action])
    js = "$.get('#{url}'"
    js += ", #{ActiveSupport::JSON.encode(args[:params])}" if args[:params]
    js += ", function(data) {$('#{args[:update]}').html(data)}" if args[:update]
    js += ');return false'
    return js
  end

  # Renders an anchor link that makes a remote call using AJAX. See
  # +remote_function+.
  def link_to_remote(args)
    link = "<a href='#' onclick='#{remote_function(args)}' title='#{args[:title]}'>#{args[:text]}</a>"
    return link
  end
end
