
export default function template(title) {
    let page = `
        <!doctype html>
            <html lang="en" data-framework="react">
            <head>
                <meta charset="utf-8">
                <title>${title}</title>
               <link rel="stylesheet" href="/static/base.css">
                <link rel="stylesheet" href="/static/index.css">
            </head>
            <body>
                <div class="todoapp" id="app"></div>
                <script src="/dist/client.js"></script>
                <script src="/dist/base.js"></script>
            </body>
        </html>
      `;
    return page;
}