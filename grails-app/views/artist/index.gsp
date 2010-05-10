<div id='collection-nav'>
<g:if test="${parent}">
    <a href='#' onclick='Collection.view("${parent}");return false'><img src='${createLinkTo(dir: 'images', file: 'folder_back.png')}' /></a>
</g:if>
<g:elseif test="${search}">
    <a href='#' onclick='Collection.search("${search}");return false'><img src='${createLinkTo(dir: 'images', file: 'folder_back.png')}' /></a>
</g:elseif>
<g:else>
    <img class='dimmed' src='${createLinkTo(dir: 'images', file: 'folder_back.png')}' />
</g:else>
<g:if test="${id}">
    <a href='#' onclick='Playlist.addParent("${id}");return false'><img src='${createLinkTo(dir: 'images', file: 'folder_add.png')}' /></a>
</g:if>
<g:else>
    <img class='dimmed' src='${createLinkTo(dir: 'images', file: 'folder_add.png')}' />
</g:else>
</div>
<div id='collection-header'>Listing for <span id='collection-label'>${label}</span></div>

<div id='collection'>
<g:if test="${dirs}">
    <ul class='dirs'>
    <g:each var="dir" in="${dirs}" status="i">
        <li class='dir'>
            <a href='#' onclick='Playlist.addParent("${dir.id}");return false' class='fileAdd' title='Add "${dir.label}"'>
                <img src='${createLinkTo(dir: 'images', file: 'folder_add.png')}' alt='Add "${dir.label}"' />
            </a>
            <a href='#' onclick='Collection.view("${dir.id}");return false' title='View "${dir.label}"'>${dir.label}</a>
        </li>
	</g:each>
    </ul>
</g:if>

<g:if test="${files}">
    <ul class='files'>
    <g:each var="file" in="${files}" status="i">
        <li class='file' id='${file.id}'>
            <a href='#' onclick='Collection.download("${file.id}");return false' class='fileDownload' title='Download "${file.label}"'><img src='${createLinkTo(dir: 'images', file: 'arrow_down.png')}' alt='Download "${file.label}"' /></a>
            <a href='#' onclick='Playlist.addItem("${file.id}");return false' class='fileAdd' title='Add "${file.label}"'><img src='${createLinkTo(dir: 'images', file: 'add.png')}' alt='Add "${file.label}"' /></a>
            ${file.label}
        </li>
    </g:each>
    </ul>
</g:if>
</div>
