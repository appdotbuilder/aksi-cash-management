import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AppLayout from '@/components/layouts/app-layout';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Capital Requests',
        href: '/capital-requests',
    },
    {
        title: 'Create New Request',
        href: '/capital-requests/create',
    },
];

export default function CreateCapitalRequest() {
    const [formData, setFormData] = useState({
        amount: '',
        purpose: '',
    });
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [processing, setProcessing] = useState(false);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setProcessing(true);
        setErrors({});

        router.post('/capital-requests', formData, {
            onError: (errors) => {
                setErrors(errors);
                setProcessing(false);
            },
            onSuccess: () => {
                setProcessing(false);
            },
        });
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
        
        // Clear error for this field
        if (errors[name]) {
            setErrors(prev => ({ ...prev, [name]: '' }));
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Capital Request - AKSI" />
            
            <div className="max-w-2xl mx-auto space-y-6">
                {/* Header */}
                <div className="text-center">
                    <div className="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span className="text-3xl">💳</span>
                    </div>
                    <h1 className="text-2xl font-bold text-gray-900">Create Capital Request</h1>
                    <p className="text-gray-600 mt-2">
                        Submit a new capital funding request for approval
                    </p>
                </div>

                {/* Form */}
                <div className="bg-white rounded-xl shadow-sm border p-6">
                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Amount Field */}
                        <div>
                            <label htmlFor="amount" className="block text-sm font-medium text-gray-700 mb-2">
                                Capital Amount <span className="text-red-500">*</span>
                            </label>
                            <div className="relative">
                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span className="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input
                                    type="number"
                                    id="amount"
                                    name="amount"
                                    step="0.01"
                                    min="0.01"
                                    value={formData.amount}
                                    onChange={handleChange}
                                    className={`block w-full pl-12 pr-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 ${
                                        errors.amount ? 'border-red-300' : 'border-gray-300'
                                    }`}
                                    placeholder="0.00"
                                    required
                                />
                            </div>
                            {errors.amount && (
                                <p className="mt-1 text-sm text-red-600">{errors.amount}</p>
                            )}
                            <p className="mt-1 text-sm text-gray-500">
                                Enter the capital amount you need in Indonesian Rupiah
                            </p>
                        </div>

                        {/* Purpose Field */}
                        <div>
                            <label htmlFor="purpose" className="block text-sm font-medium text-gray-700 mb-2">
                                Purpose <span className="text-red-500">*</span>
                            </label>
                            <textarea
                                id="purpose"
                                name="purpose"
                                rows={4}
                                value={formData.purpose}
                                onChange={handleChange}
                                className={`block w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 ${
                                    errors.purpose ? 'border-red-300' : 'border-gray-300'
                                }`}
                                placeholder="Describe what you need the capital for..."
                                maxLength={1000}
                                required
                            />
                            {errors.purpose && (
                                <p className="mt-1 text-sm text-red-600">{errors.purpose}</p>
                            )}
                            <div className="mt-1 flex justify-between text-sm text-gray-500">
                                <span>Explain the business purpose and expected outcomes</span>
                                <span>{formData.purpose.length}/1000</span>
                            </div>
                        </div>

                        {/* Process Flow Info */}
                        <div className="bg-purple-50 rounded-lg p-4">
                            <h3 className="text-sm font-medium text-purple-900 mb-2">
                                📋 Approval Process
                            </h3>
                            <div className="text-sm text-purple-800 space-y-1">
                                <div className="flex items-center space-x-2">
                                    <span className="w-5 h-5 bg-purple-200 rounded-full flex items-center justify-center text-xs font-medium">1</span>
                                    <span>Operator will review and approve your request</span>
                                </div>
                                <div className="flex items-center space-x-2">
                                    <span className="w-5 h-5 bg-purple-200 rounded-full flex items-center justify-center text-xs font-medium">2</span>
                                    <span>Finance will give final approval</span>
                                </div>
                                <div className="flex items-center space-x-2">
                                    <span className="w-5 h-5 bg-purple-200 rounded-full flex items-center justify-center text-xs font-medium">3</span>
                                    <span>Funds will be disbursed to your account</span>
                                </div>
                            </div>
                        </div>

                        {/* Common Purposes */}
                        <div className="bg-gray-50 rounded-lg p-4">
                            <h3 className="text-sm font-medium text-gray-900 mb-2">
                                💡 Common Capital Purposes
                            </h3>
                            <div className="grid grid-cols-2 gap-2 text-sm text-gray-600">
                                <div>• Inventory restocking</div>
                                <div>• Equipment purchase</div>
                                <div>• Store renovation</div>
                                <div>• Marketing campaigns</div>
                                <div>• Working capital</div>
                                <div>• Technology upgrades</div>
                                <div>• Staff training</div>
                                <div>• Business expansion</div>
                            </div>
                        </div>

                        {/* Action Buttons */}
                        <div className="flex items-center justify-end space-x-4 pt-6 border-t">
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => router.get('/capital-requests')}
                                disabled={processing}
                            >
                                Cancel
                            </Button>
                            <Button
                                type="submit"
                                disabled={processing || !formData.amount || !formData.purpose}
                                className="min-w-[120px] bg-purple-600 hover:bg-purple-700"
                            >
                                {processing ? (
                                    <div className="flex items-center space-x-2">
                                        <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                        <span>Creating...</span>
                                    </div>
                                ) : (
                                    <div className="flex items-center space-x-2">
                                        <span>✅</span>
                                        <span>Submit Request</span>
                                    </div>
                                )}
                            </Button>
                        </div>
                    </form>
                </div>

                {/* Help Section */}
                <div className="bg-gray-50 rounded-xl p-6">
                    <h3 className="text-lg font-medium text-gray-900 mb-3">💡 Request Guidelines</h3>
                    <div className="text-sm text-gray-600 space-y-2">
                        <p><strong>Minimum amount:</strong> Rp 0.01</p>
                        <p><strong>Maximum amount:</strong> Rp 999,999,999.99</p>
                        <p><strong>Processing time:</strong> Usually 3-5 business days</p>
                        <p><strong>Documentation:</strong> Be specific about the business purpose</p>
                        <p><strong>Tracking:</strong> You'll receive a unique request code to track progress</p>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}