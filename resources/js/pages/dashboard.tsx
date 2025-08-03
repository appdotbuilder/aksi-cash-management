import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/components/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';

interface Props {
    stats: {
        pending_deposits: number;
        pending_capital_requests: number;
        total_deposits: number;
        total_capital_requests: number;
        total_users?: number;
    };
    user_role: string;
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard({ stats, user_role }: Props) {
    const getRoleDisplayName = (role: string) => {
        const roleNames: Record<string, string> = {
            outlet: 'Outlet',
            sales: 'Sales Representative',
            operator: 'Operator',
            penyetor: 'Depositor',
            finance: 'Finance',
            admin: 'Administrator',
        };
        return roleNames[role] || 'Unknown';
    };

    const getRoleIcon = (role: string) => {
        const icons: Record<string, string> = {
            outlet: '🏪',
            sales: '📊',
            operator: '⚙️',
            penyetor: '💰',
            finance: '💳',
            admin: '👨‍💼',
        };
        return icons[role] || '👤';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard - AKSI" />
            
            <div className="space-y-6">
                {/* Welcome Header */}
                <div className="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                    <div className="flex items-center space-x-3 mb-2">
                        <span className="text-2xl">{getRoleIcon(user_role)}</span>
                        <h1 className="text-2xl font-bold">Welcome to AKSI</h1>
                    </div>
                    <p className="text-blue-100">
                        You're logged in as a <strong>{getRoleDisplayName(user_role)}</strong>
                    </p>
                </div>

                {/* Stats Grid */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div className="bg-white rounded-xl p-6 shadow-sm border">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="text-sm font-medium text-gray-600">Pending Deposits</h3>
                            <span className="text-2xl">⏳</span>
                        </div>
                        <div className="text-3xl font-bold text-gray-900">{stats.pending_deposits}</div>
                        <p className="text-sm text-gray-500 mt-1">Awaiting approval</p>
                    </div>

                    <div className="bg-white rounded-xl p-6 shadow-sm border">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="text-sm font-medium text-gray-600">Pending Capital</h3>
                            <span className="text-2xl">💰</span>
                        </div>
                        <div className="text-3xl font-bold text-gray-900">{stats.pending_capital_requests}</div>
                        <p className="text-sm text-gray-500 mt-1">Awaiting approval</p>
                    </div>

                    <div className="bg-white rounded-xl p-6 shadow-sm border">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="text-sm font-medium text-gray-600">Total Deposits</h3>
                            <span className="text-2xl">📈</span>
                        </div>
                        <div className="text-3xl font-bold text-gray-900">{stats.total_deposits}</div>
                        <p className="text-sm text-gray-500 mt-1">All time</p>
                    </div>

                    <div className="bg-white rounded-xl p-6 shadow-sm border">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="text-sm font-medium text-gray-600">
                                {stats.total_users ? 'Total Users' : 'Total Capital'}
                            </h3>
                            <span className="text-2xl">{stats.total_users ? '👥' : '💎'}</span>
                        </div>
                        <div className="text-3xl font-bold text-gray-900">
                            {stats.total_users || stats.total_capital_requests}
                        </div>
                        <p className="text-sm text-gray-500 mt-1">
                            {stats.total_users ? 'System users' : 'All time'}
                        </p>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        {user_role === 'outlet' && (
                            <>
                                <Link href="/cash-deposits/create">
                                    <Button className="w-full justify-start" variant="outline">
                                        <span className="mr-2">💰</span>
                                        New Cash Deposit
                                    </Button>
                                </Link>
                                <Link href="/capital-requests/create">
                                    <Button className="w-full justify-start" variant="outline">
                                        <span className="mr-2">💳</span>
                                        Request Capital
                                    </Button>
                                </Link>
                            </>
                        )}
                        
                        <Link href="/cash-deposits">
                            <Button className="w-full justify-start" variant="outline">
                                <span className="mr-2">📋</span>
                                Cash Deposits
                            </Button>
                        </Link>
                        
                        <Link href="/capital-requests">
                            <Button className="w-full justify-start" variant="outline">
                                <span className="mr-2">📊</span>
                                Capital Requests
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Role-specific Information */}
                <div className="bg-white rounded-xl p-6 shadow-sm border">
                    <h2 className="text-lg font-semibold text-gray-900 mb-4">
                        {getRoleIcon(user_role)} Your Role: {getRoleDisplayName(user_role)}
                    </h2>
                    
                    <div className="prose prose-sm text-gray-600">
                        {user_role === 'outlet' && (
                            <div>
                                <p className="mb-3"><strong>As an Outlet user, you can:</strong></p>
                                <ul className="list-disc list-inside space-y-1">
                                    <li>Create new cash deposit requests</li>
                                    <li>Submit capital funding requests</li>
                                    <li>Track the status of your requests</li>
                                    <li>View your transaction history</li>
                                </ul>
                            </div>
                        )}
                        
                        {user_role === 'sales' && (
                            <div>
                                <p className="mb-3"><strong>As a Sales Representative, you can:</strong></p>
                                <ul className="list-disc list-inside space-y-1">
                                    <li>Verify pending cash deposit requests</li>
                                    <li>Approve or reject deposit requests</li>
                                    <li>Add verification notes</li>
                                    <li>View all outlet transactions</li>
                                </ul>
                            </div>
                        )}
                        
                        {user_role === 'operator' && (
                            <div>
                                <p className="mb-3"><strong>As an Operator, you can:</strong></p>
                                <ul className="list-disc list-inside space-y-1">
                                    <li>Approve sales-verified cash deposits</li>
                                    <li>Assign depositors to cash deposits</li>
                                    <li>Review and approve capital requests</li>
                                    <li>Manage workflow assignments</li>
                                </ul>
                            </div>
                        )}
                        
                        {user_role === 'finance' && (
                            <div>
                                <p className="mb-3"><strong>As a Finance user, you can:</strong></p>
                                <ul className="list-disc list-inside space-y-1">
                                    <li>Perform final reconciliation of cash deposits</li>
                                    <li>Give final approval for capital requests</li>
                                    <li>Disburse approved capital funds</li>
                                    <li>Generate financial reports</li>
                                </ul>
                            </div>
                        )}
                        
                        {user_role === 'penyetor' && (
                            <div>
                                <p className="mb-3"><strong>As a Depositor, you can:</strong></p>
                                <ul className="list-disc list-inside space-y-1">
                                    <li>View assigned cash deposit tasks</li>
                                    <li>Execute cash deposit operations</li>
                                    <li>Update deposit status</li>
                                    <li>Coordinate with operations team</li>
                                </ul>
                            </div>
                        )}
                        
                        {user_role === 'admin' && (
                            <div>
                                <p className="mb-3"><strong>As an Administrator, you can:</strong></p>
                                <ul className="list-disc list-inside space-y-1">
                                    <li>Manage user accounts and roles</li>
                                    <li>View system-wide statistics</li>
                                    <li>Configure system settings</li>
                                    <li>Access all transactions and reports</li>
                                </ul>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}