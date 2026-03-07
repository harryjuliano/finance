import AppLayout from '@/Layouts/AppLayout';
import { Head } from '@inertiajs/react';
import Card from '@/Components/Card';

export default function Index({ modules, recentPaymentRequests, recentTransactions, recentActivities }) {
    const formatNumber = (value) => new Intl.NumberFormat('id-ID').format(value ?? 0);

    return (
        <>
            <Head title="Phase 1 Tracker" />

            <div className="space-y-6">
                <Card>
                    <div className="space-y-2">
                        <h1 className="text-xl font-semibold text-gray-800 dark:text-gray-100">Phase 1 - Backend & Frontend Coverage</h1>
                        <p className="text-sm text-gray-600 dark:text-gray-400">
                            Ringkasan progress modul core: organization master, user access, akun kas/bank, transaksi, approval, lampiran,
                            dan audit log.
                        </p>
                    </div>
                </Card>

                <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    {modules.map((module) => (
                        <Card key={module.title}>
                            <div className="space-y-3">
                                <div>
                                    <h2 className="text-base font-semibold text-gray-800 dark:text-gray-100">{module.title}</h2>
                                    <p className="text-sm text-gray-500 dark:text-gray-400">{module.description}</p>
                                </div>
                                <div className="space-y-2">
                                    {module.items.map((item) => (
                                        <div key={item.label} className="flex items-center justify-between rounded-md border border-gray-200 dark:border-gray-800 px-3 py-2">
                                            <span className="text-sm text-gray-600 dark:text-gray-300">{item.label}</span>
                                            <span className="font-semibold text-gray-900 dark:text-gray-100">{formatNumber(item.value)}</span>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </Card>
                    ))}
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <Card>
                        <h3 className="font-semibold mb-3 text-gray-800 dark:text-gray-100">Recent Payment Requests</h3>
                        <div className="space-y-2">
                            {recentPaymentRequests.length ? (
                                recentPaymentRequests.map((item) => (
                                    <div key={item.id} className="rounded-md border border-gray-200 dark:border-gray-800 px-3 py-2 text-sm">
                                        <div className="font-medium">{item.request_no}</div>
                                        <div className="text-gray-500 dark:text-gray-400">{item.request_date} • {item.status}</div>
                                    </div>
                                ))
                            ) : (
                                <p className="text-sm text-gray-500">Belum ada data.</p>
                            )}
                        </div>
                    </Card>

                    <Card>
                        <h3 className="font-semibold mb-3 text-gray-800 dark:text-gray-100">Recent Cash Transactions</h3>
                        <div className="space-y-2">
                            {recentTransactions.length ? (
                                recentTransactions.map((item) => (
                                    <div key={item.id} className="rounded-md border border-gray-200 dark:border-gray-800 px-3 py-2 text-sm">
                                        <div className="font-medium">{item.reference_no}</div>
                                        <div className="text-gray-500 dark:text-gray-400">{item.transaction_date} • {item.type} • {item.status}</div>
                                    </div>
                                ))
                            ) : (
                                <p className="text-sm text-gray-500">Belum ada data.</p>
                            )}
                        </div>
                    </Card>

                    <Card>
                        <h3 className="font-semibold mb-3 text-gray-800 dark:text-gray-100">Recent Audit Logs</h3>
                        <div className="space-y-2">
                            {recentActivities.length ? (
                                recentActivities.map((item) => (
                                    <div key={item.id} className="rounded-md border border-gray-200 dark:border-gray-800 px-3 py-2 text-sm">
                                        <div className="font-medium">{item.module}</div>
                                        <div className="text-gray-500 dark:text-gray-400">{item.document_type} • {item.action}</div>
                                    </div>
                                ))
                            ) : (
                                <p className="text-sm text-gray-500">Belum ada data.</p>
                            )}
                        </div>
                    </Card>
                </div>
            </div>
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} />;
