import Button from '@/Components/Button';
import Card from '@/Components/Card';
import Table from '@/Components/Table';
import AppLayout from '@/Layouts/AppLayout';
import { Head, Link, router } from '@inertiajs/react';
import { IconCheck, IconClockHour4, IconFileText, IconWallet } from '@tabler/icons-react';
import { useState } from 'react';

const statusClassMap = {
    ready: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
    queued: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
    paid: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
};

export default function Index({ summary, executionQueue, recentExecutions, paymentMethodOptions, sourceAccountOptions }) {
    const buildFilteredUrl = (statusFilter) => {
        if (!statusFilter) {
            return route('apps.cash-management.payment-requests.index');
        }

        return `${route('apps.cash-management.payment-requests.index')}?status=${statusFilter}`;
    };

    const [executionForm, setExecutionForm] = useState(() => executionQueue.reduce((carry, item) => ({
        ...carry,
        [item.id]: {
            payment_method: item.payment_method !== '-' ? item.payment_method : paymentMethodOptions[0] ?? '',
            source_account: item.source_account !== '-' ? item.source_account : sourceAccountOptions[0] ?? '',
        },
    }), {}));

    const updateExecutionField = (id, field, value) => {
        setExecutionForm((prev) => ({
            ...prev,
            [id]: {
                ...(prev[id] ?? {}),
                [field]: value,
            },
        }));
    };

    const markAsPaid = (id) => {
        const payload = executionForm[id] ?? { payment_method: '', source_account: '' };

        router.post(route('apps.cash-management.payment-requests.mark-paid', id), payload, {
            preserveScroll: true,
        });
    };

    return (
        <>
            <Head title="Cash Execution" />

            <div className="space-y-4">
                <Card>
                    <div className="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h1 className="text-xl font-semibold text-gray-800 dark:text-gray-100">Cash Execution</h1>
                            <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">Antrian payment request yang diambil dari transaksi Payment Request.</p>
                        </div>
                        <Link href={route('apps.cash-management.payment-requests.index')} className="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-200 dark:bg-sky-900/30 dark:text-sky-300 dark:hover:bg-sky-900/50">
                            Buka Payment Requests
                        </Link>
                    </div>

                    <div className="mt-4 grid grid-cols-1 gap-3 md:grid-cols-4">
                        {summary.map((item) => (
                            <div key={item.label} className="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-900/40">
                                <p className="text-xs text-gray-500 dark:text-gray-400">{item.label}</p>
                                <p className="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-100">
                                    {item.status_filter ? (
                                        <Link href={buildFilteredUrl(item.status_filter)} className="hover:text-blue-600 hover:underline dark:hover:text-blue-300">
                                            {item.value}
                                        </Link>
                                    ) : (
                                        item.value
                                    )}
                                </p>
                            </div>
                        ))}
                    </div>
                </Card>

                <Table.Card title="Antrian Payment Request">
                    <Table>
                        <Table.Thead>
                            <tr>
                                <Table.Th>Request No</Table.Th>
                                <Table.Th>Vendor</Table.Th>
                                <Table.Th>Jatuh Tempo</Table.Th>
                                <Table.Th>Metode</Table.Th>
                                <Table.Th>Source Account</Table.Th>
                                <Table.Th>Amount</Table.Th>
                                <Table.Th>Status</Table.Th>
                                <Table.Th>Aksi</Table.Th>
                            </tr>
                        </Table.Thead>
                        <Table.Tbody>
                            {executionQueue.map((item) => (
                                <tr key={item.request_no} className="hover:bg-gray-100 dark:hover:bg-gray-900">
                                    <Table.Td><Link href={`${route('apps.cash-management.payment-requests.index')}?search=${encodeURIComponent(item.request_no)}`} className="text-blue-600 hover:underline dark:text-blue-400">{item.request_no}</Link></Table.Td>
                                    <Table.Td>{item.vendor}</Table.Td>
                                    <Table.Td>{item.due_date}</Table.Td>
                                    <Table.Td>
                                        <select
                                            value={executionForm[item.id]?.payment_method ?? ''}
                                            onChange={(event) => updateExecutionField(item.id, 'payment_method', event.target.value)}
                                            className="w-full rounded-md border border-gray-300 bg-white px-2 py-1 text-sm dark:border-gray-700 dark:bg-gray-800"
                                            disabled={item.status === 'paid'}
                                        >
                                            {paymentMethodOptions.map((method) => (
                                                <option key={method} value={method}>{method}</option>
                                            ))}
                                        </select>
                                    </Table.Td>
                                    <Table.Td>
                                        <select
                                            value={executionForm[item.id]?.source_account ?? ''}
                                            onChange={(event) => updateExecutionField(item.id, 'source_account', event.target.value)}
                                            className="w-full rounded-md border border-gray-300 bg-white px-2 py-1 text-sm dark:border-gray-700 dark:bg-gray-800"
                                            disabled={item.status === 'paid'}
                                        >
                                            {sourceAccountOptions.map((account) => (
                                                <option key={account} value={account}>{account}</option>
                                            ))}
                                        </select>
                                    </Table.Td>
                                    <Table.Td>{item.amount}</Table.Td>
                                    <Table.Td>
                                        <span className={`inline-flex rounded-full px-2 py-1 text-xs font-semibold ${statusClassMap[item.status]}`}>
                                            {item.status_label}
                                        </span>
                                    </Table.Td>
                                    <Table.Td>
                                        <Button
                                            type={'button'}
                                            variant={'emerald'}
                                            label={item.status === 'paid' ? 'Sudah Paid' : 'Mark as Paid'}
                                            onClick={() => markAsPaid(item.id)}
                                            disabled={item.status === 'paid'}
                                        />
                                    </Table.Td>
                                </tr>
                            ))}
                        </Table.Tbody>
                    </Table>
                </Table.Card>

                <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <Card>
                        <h2 className="mb-3 flex items-center gap-2 font-semibold text-gray-800 dark:text-gray-100"><IconClockHour4 size={18} /> Step Eksekusi</h2>
                        <ul className="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li className="flex items-start gap-2"><IconFileText size={16} className="mt-0.5 text-blue-500" /> Pilih request berstatus approved dan cek dokumen pendukung.</li>
                            <li className="flex items-start gap-2"><IconWallet size={16} className="mt-0.5 text-amber-500" /> Verifikasi saldo sumber dana termasuk biaya transfer.</li>
                            <li className="flex items-start gap-2"><IconCheck size={16} className="mt-0.5 text-emerald-500" /> Pilih metode + source account, lalu update status paid.</li>
                        </ul>
                    </Card>

                    <Card>
                        <h2 className="mb-3 font-semibold text-gray-800 dark:text-gray-100">Eksekusi Terakhir</h2>
                        <ul className="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            {recentExecutions.map((item) => (
                                <li key={item.reference} className="rounded-md border border-gray-200 p-2 dark:border-gray-800">
                                    <p className="font-medium">{item.reference}</p>
                                    <p>{item.time} • {item.bank_channel}</p>
                                    <p className="text-emerald-600 dark:text-emerald-400">{item.amount}</p>
                                </li>
                            ))}
                        </ul>
                    </Card>
                </div>
            </div>
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} />;
