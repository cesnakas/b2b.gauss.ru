let svgUse = {
    run: function() {
        ( function( window, document )
        {
            'use strict';
            
            let file     = '/local/client/build/sprite.svg',
                revision = INLINE_SVG_REVISION;
            
            if( !document.createElementNS || !document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' ).createSVGRect )
                return true;
            
            let isLocalStorage = 'localStorage' in window && window[ 'localStorage' ] !== null,
                request,
                data,
                insertIT = function()
                {
                    let g = document.createElement('div');
                    g.id = 'svg-sprite';
                    g.className = 'hidden';
                    document.body.appendChild(g);
                    document.getElementById('svg-sprite').insertAdjacentHTML( 'afterbegin', data );
                    svg4everybody({
                        polyfill: true // polyfill <use> elements for External Content
                    });
                },
                insert = function()
                {
                    if( document.body ) insertIT();
                    else document.addEventListener( 'DOMContentLoaded', insertIT );
                };
            
            if( isLocalStorage && localStorage.getItem( 'inlineSVGrev' ) == revision )
            {
                data = localStorage.getItem( 'inlineSVGdata' );
                if( data )
                {
                    insert();
                    return true;
                }
            }
            
            try
            {
                file += '?_='+ new Date().getTime();
                request = new XMLHttpRequest();
                request.open( 'GET', file, true );
                request.onload = function()
                {
                    if( request.status >= 200 && request.status < 400 )
                    {
                        data = request.responseText;
                        insert();
                        if( isLocalStorage )
                        {
                            localStorage.setItem( 'inlineSVGdata',  data );
                            localStorage.setItem( 'inlineSVGrev',   revision );
                        }
                    }
                };
                request.send();
            }
            catch( e ){}
            
        }( window, document ) );
    }
};

module.exports = svgUse;
