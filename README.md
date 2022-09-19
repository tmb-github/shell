# shell
Original SPA website shell

Template files for making SPA website with PHP backend and original JavaScript frontend.

* JavaScript and style sheets are protected with CSP-3 'strict-dynamic'
* Code splitting and style splitting accomplished per-page with dynamically loaded modules.
* Main JS functions are accessible to dynamically loaded modules, and vice-versa via original binding mechanism.
* Code may be served minified or beautified.
* Minification provided via Google Closure Compiler; requires local Java installation to build minified code for deployment
