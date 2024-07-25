// parent_id = 0 for header menu
// parent_id = digit number for submenu
// parent_id = '' single menu
export default [
    {
        id: 40,
        parent_id: 0,
        header: 'MASTER DATA',
    },
    {
        id: 41,
        parent_id: 40,
        label: 'Admin',
        name: 'admin',
        icon: 'fa-users',
        permission: ['admin_read']
    },
    {
        id: 42,
        parent_id: 40,
        label: 'List Kategori',
        name: 'list-category',
        icon: 'fa-list',
        permission: ['list_category_read']
    },
    {
        id: 43,
        parent_id: 40,
        label: 'Hak Akses',
        name: 'hak-akses',
        icon: '',
        permission: ['access_read']
    },
    {
        id: 60,
        parent_id: 0,
        header: 'Lain - Lain',
    },
    {
        id: 61,
        parent_id: 60,
        label: 'Pengaturan',
        name: 'config',
        icon: 'fa-cogs',
        permission: ['setting_read']
    },
    {
        id: 62,
        parent_id: 60,
        label: 'Log',
        name: 'log',
        icon: 'fa-history',
        permission: ['setting_read']
    },
    {
        id: 63,
        parent_id: '',
        label: 'Logout',
        name: 'logout',
        icon: 'fa-sign-out-alt'
    }
];