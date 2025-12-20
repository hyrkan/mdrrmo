import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import Chart from 'chart.js/auto';
window.Chart = Chart;

import toastr from 'toastr';
window.toastr = toastr;

import DataTable from 'datatables.net-dt';
DataTable(window, jQuery);
window.DataTable = DataTable;

import '@fontsource/instrument-sans/400.css';
import '@fontsource/instrument-sans/500.css';
import '@fontsource/instrument-sans/600.css';
