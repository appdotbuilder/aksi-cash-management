import React from 'react';
import { AppShell } from '@/components/app-shell';
import { type BreadcrumbItem } from '@/types';

interface AppLayoutProps {
    children: React.ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({ children, breadcrumbs }: AppLayoutProps) {
    return (
        <AppShell breadcrumbs={breadcrumbs}>
            {children}
        </AppShell>
    );
}