import { usePage } from '@inertiajs/react';
import {
    IconAdjustments,
    IconBuildingBank,
    IconChecklist,
    IconClipboardCheck,
    IconLayout2,
    IconReportAnalytics,
    IconSettings,
    IconTransform,
    IconUserBolt,
    IconUserShield,
    IconUsers,
    IconTable,
    IconCirclePlus,
} from '@tabler/icons-react';
import hasAnyPermission from './Permissions';
import React from 'react';

export default function Menu() {
    const { url } = usePage();

    const menuNavigation = [
        {
            title: 'Cash Management',
            permissions: hasAnyPermission(['dashboard-access']),
            details: [
                {
                    title: 'Dashboard',
                    href: '/apps/dashboard',
                    active: url.startsWith('/apps/dashboard'),
                    icon: <IconLayout2 size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },
                {
                    title: 'Payment Requests',
                    href: '/apps/cash-management/payment-requests',
                    active: url.startsWith('/apps/cash-management/payment-requests'),
                    icon: <IconBuildingBank size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },

                {
                    title: 'Phase 1 Tracker',
                    href: '/apps/cash-management/phase-1',
                    active: url.startsWith('/apps/cash-management/phase-1'),
                    icon: <IconChecklist size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },
                {
                    title: 'Cash Execution',
                    href: '/apps/cash-management/treasury',
                    active: url.startsWith('/apps/cash-management/treasury'),
                    icon: <IconTransform size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },
                {
                    title: 'Approvals',
                    href: '/apps/cash-management/approvals',
                    active: url.startsWith('/apps/cash-management/approvals'),
                    icon: <IconClipboardCheck size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },
                {
                    title: 'Reconciliation',
                    href: '/apps/cash-management/reconciliation',
                    active: url.startsWith('/apps/cash-management/reconciliation'),
                    icon: <IconAdjustments size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },
                {
                    title: 'Reports',
                    href: '/apps/cash-management/reports',
                    active: url.startsWith('/apps/cash-management/reports'),
                    icon: <IconReportAnalytics size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },
                {
                    title: 'Administration',
                    href: '/apps/cash-management/administration',
                    active: url.startsWith('/apps/cash-management/administration'),
                    icon: <IconSettings size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['dashboard-access']),
                },
            ],
        },
        {
            title: 'User Management',
            permissions:
                hasAnyPermission(['permissions-access']) ||
                hasAnyPermission(['roles-access']) ||
                hasAnyPermission(['users-access']),
            details: [
                {
                    title: 'Hak Akses',
                    href: '/apps/permissions',
                    active: url.startsWith('/apps/permissions'),
                    icon: <IconUserBolt size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['permissions-access']),
                },
                {
                    title: 'Akses Group',
                    href: '/apps/roles',
                    active: url.startsWith('/apps/roles'),
                    icon: <IconUserShield size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['roles-access']),
                },
                {
                    title: 'Pengguna',
                    icon: <IconUsers size={20} strokeWidth={1.5} />,
                    permissions: hasAnyPermission(['users-access']),
                    subdetails: [
                        {
                            title: 'Data Pengguna',
                            href: '/apps/users',
                            icon: <IconTable size={20} strokeWidth={1.5} />,
                            active: url === '/apps/users',
                            permissions: hasAnyPermission(['users-data']),
                        },
                        {
                            title: 'Tambah Data Pengguna',
                            href: '/apps/users/create',
                            icon: <IconCirclePlus size={20} strokeWidth={1.5} />,
                            active: url === '/apps/users/create',
                            permissions: hasAnyPermission(['users-create']),
                        },
                    ],
                },
            ],
        },
    ];

    return menuNavigation;
}
