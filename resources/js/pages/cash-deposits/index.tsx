import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AppLayout from '@/components/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';

interface CashDeposit {
    id: number;
    deposit_code: string;
    amount: string;
    description: string | null;
    status: string;
    created_at: string;
    outlet: {
        name: string;
        outlet_code: string | null;
    };
    sales: {
        name: string;
    } | null;
    operator: {
        name: string;
    } | null;
    finance: {
        name: string;
    } | null;
}

interface Props {
    deposits: {
        data: CashDeposit[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    user_role: string;
    [key: string]: unknown;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Cash Deposits',
        href: '/cash-deposits',
    },
];

export default function CashDepositsIndex({ deposits, user_role }: Props) {
    const getStatusBadge = (status: string) => {
        const statusConfig: Record<string, { label: string; color: string }> = {
            pending: { label: 'Pending', color: 'bg-yellow-100 text-yellow-800' },
            sales_approved: { label: 'Sales Approved', color: 'bg-blue-100 text-blue-800' },
            operator_approved: { label: 'Operator Approved', color: 'bg-purple-100 text-purple-800' },
            finance_approved: { label: 'Finance Approved', color: 'bg-green-100 text-green-800' },
            rejected: { label: 'Rejected', color: 'bg-red-100 text-red-800' },
        };

        const config = statusConfig[status] || { label: status, color: 'bg-gray-100 text-gray-800' };
        
        return (
            <span className={`px-2 py-1 text-xs font-medium rounded-full ${config.color}`}>
                {config.label}
            </span>
        );
    };

    const formatCurrency = (amount: string) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
        }).format(parseFloat(amount));
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Cash Deposits - AKSI" />
            
            <div className="space-y-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">💰 Cash Deposits</h1>
                        <p className="text-gray-600">
                            Manage and track cash deposit requests
                        </p>
                    </div>
                    
                    {user_role === 'outlet' && (
                        <Link href="/cash-deposits/create">
                            <Button>
                                <span className="mr-2">➕</span>
                                New Deposit Request
                            </Button>
                        </Link>
                    )}
                </div>

                {/* Stats Summary */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div className="bg-white rounded-lg p-4 shadow-sm border">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600">Total Deposits</p>
                                <p className="text-2xl font-bold text-gray-900">{deposits.total}</p>
                            </div>
                            <span className="text-2xl">📊</span>
                        </div>
                    </div>
                    
                    <div className="bg-white rounded-lg p-4 shadow-sm border">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600">This Page</p>
                                <p className="text-2xl font-bold text-gray-900">{deposits.data.length}</p>
                            </div>
                            <span className="text-2xl">📋</span>
                        </div>
                    </div>
                    
                    <div className="bg-white rounded-lg p-4 shadow-sm border">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600">Current Page</p>
                                <p className="text-2xl font-bold text-gray-900">{deposits.current_page}</p>
                            </div>
                            <span className="text-2xl">📄</span>
                        </div>
                    </div>
                    
                    <div className="bg-white rounded-lg p-4 shadow-sm border">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600">Total Pages</p>
                                <p className="text-2xl font-bold text-gray-900">{deposits.last_page}</p>
                            </div>
                            <span className="text-2xl">📚</span>
                        </div>
                    </div>
                </div>

                {/* Deposits Table */}
                <div className="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div className="px-6 py-4 border-b">
                        <h2 className="text-lg font-semibold text-gray-900">Deposit Requests</h2>
                    </div>
                    
                    {deposits.data.length === 0 ? (
                        <div className="text-center py-12">
                            <span className="text-6xl mb-4 block">📝</span>
                            <h3 className="text-lg font-medium text-gray-900 mb-2">No deposits found</h3>
                            <p className="text-gray-600 mb-4">
                                {user_role === 'outlet' 
                                    ? "You haven't created any deposit requests yet."
                                    : "No deposit requests available for review."
                                }
                            </p>
                            {user_role === 'outlet' && (
                                <Link href="/cash-deposits/create">
                                    <Button>Create Your First Deposit Request</Button>
                                </Link>
                            )}
                        </div>
                    ) : (
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Deposit Code
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Outlet
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {deposits.data.map((deposit) => (
                                        <tr key={deposit.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {deposit.deposit_code}
                                                </div>
                                                {deposit.description && (
                                                    <div className="text-sm text-gray-500 truncate max-w-xs">
                                                        {deposit.description}
                                                    </div>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm text-gray-900">{deposit.outlet.name}</div>
                                                {deposit.outlet.outlet_code && (
                                                    <div className="text-sm text-gray-500">{deposit.outlet.outlet_code}</div>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {formatCurrency(deposit.amount)}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {getStatusBadge(deposit.status)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {formatDate(deposit.created_at)}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <Link href={`/cash-deposits/${deposit.id}`}>
                                                    <Button variant="outline" size="sm">
                                                        View Details
                                                    </Button>
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {deposits.last_page > 1 && (
                    <div className="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 rounded-lg">
                        <div className="flex flex-1 justify-between sm:hidden">
                            {deposits.current_page > 1 && (
                                <Button
                                    variant="outline"
                                    onClick={() => router.get(`/cash-deposits?page=${deposits.current_page - 1}`)}
                                >
                                    Previous
                                </Button>
                            )}
                            {deposits.current_page < deposits.last_page && (
                                <Button
                                    variant="outline"
                                    onClick={() => router.get(`/cash-deposits?page=${deposits.current_page + 1}`)}
                                >
                                    Next
                                </Button>
                            )}
                        </div>
                        <div className="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p className="text-sm text-gray-700">
                                    Showing page <span className="font-medium">{deposits.current_page}</span> of{' '}
                                    <span className="font-medium">{deposits.last_page}</span> ({deposits.total} total results)
                                </p>
                            </div>
                            <div className="flex space-x-2">
                                {deposits.current_page > 1 && (
                                    <Button
                                        variant="outline"
                                        onClick={() => router.get(`/cash-deposits?page=${deposits.current_page - 1}`)}
                                    >
                                        Previous
                                    </Button>
                                )}
                                {deposits.current_page < deposits.last_page && (
                                    <Button
                                        variant="outline"
                                        onClick={() => router.get(`/cash-deposits?page=${deposits.current_page + 1}`)}
                                    >
                                        Next
                                    </Button>
                                )}
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}