$(document).ready( function () {

    $('#tabela').tableExport({
        bootstrap: true,
        formats: ['txt'],
        headers: false,
        ignoreCols: [0,2],

    },onclick.);
} );