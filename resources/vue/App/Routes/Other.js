// Pages
import ConfigIndex from '../Pages/ConfigIndex.vue';
import LogIndex from '../Pages/LogIndex.vue';

export default [
    {
        name: 'config',
        path: 'config',
        component: ConfigIndex,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: null, active: true }
            ],
            pageTitle: 'Pengaturan'
        }
    },
    {
        name: 'log',
        path: 'log',
        params : {table_name:'log'},
        component: LogIndex,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: null, active: true }
            ],
            pageTitle: 'User Activity Log'
        }
    },
]