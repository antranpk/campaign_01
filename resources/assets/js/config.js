export const config = {
    keyMap : window.Laravel.key_google_map,
    zoom: 15,
    maxFileUpload: 10,
    maxSizeFile: 0.49,
}

// Config Editor Quill
export const editorOption = {
    modules: {
        history: {
          delay: 1000,
          maxStack: 50,
          userOnly: false
        },
        toolbar: [
            ['bold', 'italic', 'underline', 'strike', { 'header': 1 }, { 'header': 2 }],
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['blockquote', 'code-block'],
            ['link', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            ['fullscreen']
        ],
        imageImport: true,
        imageResize: {
          displaySize: true
        }
    }
}

// Config VueTimeago
export const timeago = {
    name: 'timeago', // component name, `timeago` by default
    locale: 'en-US',
    locales: {
        // you will need json-loader in webpack 1
        'en-US': require('vue-timeago/locales/en-US.json')
    }
}

// Config VueTimeago
export const topProgressBar = {
    color: '#08ddc1',
    failedColor: '#ff5e3a',
    thickness: '4px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    }
}

export default {
    config,
    editorOption,
    timeago,
    topProgressBar
}
