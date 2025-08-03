import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

interface Props {
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
            role: string;
        } | null;
    };
    [key: string]: unknown;
}

export default function Welcome({ auth }: Props) {
    return (
        <>
            <Head title="AKSI - Cash Deposit & Capital Management System" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
                {/* Header */}
                <header className="bg-white shadow-sm">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between items-center py-4">
                            <div className="flex items-center space-x-3">
                                <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span className="text-white font-bold text-lg">A</span>
                                </div>
                                <h1 className="text-2xl font-bold text-gray-900">AKSI</h1>
                            </div>
                            
                            <nav className="flex items-center space-x-4">
                                {auth.user ? (
                                    <Link
                                        href="/dashboard"
                                        className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                    >
                                        Dashboard
                                    </Link>
                                ) : (
                                    <div className="flex space-x-3">
                                        <Link
                                            href="/login"
                                            className="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
                                        >
                                            Login
                                        </Link>
                                        <Link
                                            href="/register"
                                            className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                                        >
                                            Register
                                        </Link>
                                    </div>
                                )}
                            </nav>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div className="text-center mb-16">
                        <h1 className="text-5xl font-bold text-gray-900 mb-6">
                            💰 AKSI
                        </h1>
                        <p className="text-xl text-gray-600 mb-4">
                            Cash Deposit & Capital Management System
                        </p>
                        <p className="text-lg text-gray-500 max-w-3xl mx-auto">
                            Streamline your outlet operations with our comprehensive system for managing cash deposits and capital requests. 
                            Built for outlets, sales teams, operators, and finance departments.
                        </p>
                    </div>

                    {/* Features Grid */}
                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">🏪</span>
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Outlet Management</h3>
                            <p className="text-gray-600 text-sm">
                                Outlets can easily initiate cash deposit requests and request capital funding with a simple, intuitive interface.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">📊</span>
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Multi-Level Approval</h3>
                            <p className="text-gray-600 text-sm">
                                Robust approval workflow: Sales verification → Operator approval → Finance reconciliation for complete transparency.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">💳</span>
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Capital Requests</h3>
                            <p className="text-gray-600 text-sm">
                                Streamlined capital request process with operator and finance approvals, plus automated fund disbursement tracking.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                                <span className="text-2xl">👥</span>
                            </div>
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Role-Based Access</h3>
                            <p className="text-gray-600 text-sm">
                                Six distinct user roles: Outlet, Sales, Operator, Depositor, Finance, and Admin - each with tailored permissions.
                            </p>
                        </div>
                    </div>

                    {/* Workflow Visualization */}
                    <div className="bg-white rounded-xl p-8 shadow-lg mb-16">
                        <h2 className="text-2xl font-bold text-gray-900 mb-8 text-center">Cash Deposit Workflow</h2>
                        <div className="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-4">
                            <div className="flex flex-col items-center p-4 bg-blue-50 rounded-lg min-w-0 flex-1">
                                <div className="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mb-3">
                                    <span className="text-white font-bold">1</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-1">Outlet Request</h3>
                                <p className="text-sm text-gray-600 text-center">Outlet initiates cash deposit request</p>
                            </div>
                            
                            <div className="hidden md:block text-gray-400">→</div>
                            
                            <div className="flex flex-col items-center p-4 bg-green-50 rounded-lg min-w-0 flex-1">
                                <div className="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mb-3">
                                    <span className="text-white font-bold">2</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-1">Sales Verification</h3>
                                <p className="text-sm text-gray-600 text-center">Sales rep verifies and approves</p>
                            </div>
                            
                            <div className="hidden md:block text-gray-400">→</div>
                            
                            <div className="flex flex-col items-center p-4 bg-purple-50 rounded-lg min-w-0 flex-1">
                                <div className="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mb-3">
                                    <span className="text-white font-bold">3</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-1">Operator Approval</h3>
                                <p className="text-sm text-gray-600 text-center">Operator approves and assigns depositor</p>
                            </div>
                            
                            <div className="hidden md:block text-gray-400">→</div>
                            
                            <div className="flex flex-col items-center p-4 bg-orange-50 rounded-lg min-w-0 flex-1">
                                <div className="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mb-3">
                                    <span className="text-white font-bold">4</span>
                                </div>
                                <h3 className="font-semibold text-gray-900 mb-1">Finance Reconciliation</h3>
                                <p className="text-sm text-gray-600 text-center">Finance performs final reconciliation</p>
                            </div>
                        </div>
                    </div>

                    {/* Stats Preview */}
                    <div className="grid md:grid-cols-3 gap-8 mb-16">
                        <div className="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                            <h3 className="text-3xl font-bold mb-2">Fast</h3>
                            <p className="text-blue-100">Streamlined approval process reduces processing time by 70%</p>
                        </div>
                        <div className="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                            <h3 className="text-3xl font-bold mb-2">Secure</h3>
                            <p className="text-green-100">Multi-level approval ensures complete transaction security</p>
                        </div>
                        <div className="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                            <h3 className="text-3xl font-bold mb-2">Transparent</h3>
                            <p className="text-purple-100">Real-time tracking and complete audit trail for all transactions</p>
                        </div>
                    </div>

                    {/* CTA Section */}
                    <div className="text-center bg-white rounded-xl p-12 shadow-lg">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">
                            Ready to Streamline Your Operations? 🚀
                        </h2>
                        <p className="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                            Join hundreds of outlets already using AKSI to manage their cash deposits and capital requests efficiently.
                        </p>
                        
                        {auth.user ? (
                            <div className="space-y-4">
                                <p className="text-gray-600">Welcome back, {auth.user.name}!</p>
                                <Link href="/dashboard">
                                    <Button size="lg" className="bg-blue-600 hover:bg-blue-700 text-lg px-8 py-3">
                                        Go to Dashboard
                                    </Button>
                                </Link>
                            </div>
                        ) : (
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link href="/register">
                                    <Button size="lg" className="bg-blue-600 hover:bg-blue-700 text-lg px-8 py-3">
                                        Get Started Free
                                    </Button>
                                </Link>
                                <Link href="/login">
                                    <Button variant="outline" size="lg" className="text-lg px-8 py-3">
                                        Sign In
                                    </Button>
                                </Link>
                            </div>
                        )}
                    </div>
                </main>

                {/* Footer */}
                <footer className="bg-gray-900 text-white py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center">
                            <div className="flex items-center justify-center space-x-3 mb-4">
                                <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span className="text-white font-bold">A</span>
                                </div>
                                <h3 className="text-xl font-bold">AKSI</h3>
                            </div>
                            <p className="text-gray-400 mb-4">
                                Cash Deposit & Capital Management System
                            </p>
                            <p className="text-gray-500 text-sm">
                                © 2024 AKSI. Built with Laravel & React. All rights reserved.
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </>
    );
}