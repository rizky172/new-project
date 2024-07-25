// Pages
import AdminDetail from '../Pages/AdminDetail.vue';
import AdminIndex from '../Pages/AdminIndex.vue';
import CategoryIndex from '../Pages/CategoryIndex.vue';
import CategoryList from '../Pages/CategoryList.vue';
import HakAksesDetail from '../Pages/HakAksesDetail.vue';
import HakAksesIndex from '../Pages/HakAksesIndex.vue';

export default [
    {
        name: 'admin',
        path: 'account',
        component: AdminIndex,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: null, active: true }
            ],
            pageTitle: 'Admin'
        }
    },
    {
        name: 'admin-create',
        path: 'account/create',
        component: AdminDetail,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: 'Admin', url: '/account' },
                { title: null, active: true }
            ],
            pageTitle: 'Create Admin'
        }
    },
    {
        name: 'admin-detail',
        path: 'account/detail/:id',
        component: AdminDetail,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: 'Admin', url: '/account' },
                { title: null, active: true }
            ],
            pageTitle: 'Detail Admin'
        }
    },
    {
        name: 'hak-akses',
        path: 'hak-akses',
        component: HakAksesIndex,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: null, active: true }
            ],
            pageTitle: 'Hak Akses'
        }
    },
    {
        name: 'hak-akses-detail',
        path: 'hak-akses/detail/:id',
        component: HakAksesDetail,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: 'Hak Akses', url: '/hak-akses' },
                { title: null, active: true }
            ],
            pageTitle: 'Detail Hak Akses'
        }
    },
    {
        name: 'hak-akses-create',
        path: 'hak-akses/create',
        component: HakAksesDetail,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: 'Hak Akses', url: '/hak-akses' },
                { title: null, active: true }
            ],
            pageTitle: 'Create Hak Akses'
        }
    },
    {
        name: 'list-category',
        path: 'category',
        component: CategoryList,
        meta: {
            breadcrumb: [
                { title: 'Home', url: '/' },
                { title: null, active: true }
            ],
            pageTitle: 'List Kategori'
        }
    },
]