import Card from '@/Components/Card';
import Table from '@/Components/Table';
import AppLayout from '@/Layouts/AppLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';

const statusClassMap = {
    submitted: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
    under_verification: 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
    verified: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
    waiting_approval: 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
    approved: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
    rejected: 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
    revision_required: 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300',
};

export default function Index({ summary, approvalQueue }) {
    const [reasons, setReasons] = useState({});

    const updateReason = (id, value) => {
        setReasons((prev) => ({
            ...prev,
            [id]: value,
        }));
    };

    const postAction = (routeName, id, payload = {}) => {
        router.post(route(routeName, id), payload, {
            preserveScroll: true,
        });
    };

    const canVerify = (status) => ['submitted', 'under_verification'].includes(status);
    const canApprove = (status) => ['verified', 'waiting_approval'].includes(status);

    return (
        <>
            <Head title="Approval Workflow" />

            <div className="space-y-4">
                <Card>
                    <div className="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <h1 className="text-xl font-semibold text-gray-800 dark:text-gray-100">Approval Workflow</h1>
                            <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">Proses verifikasi dan approval Payment Request dari frontend yang langsung mengeksekusi workflow backend.</p>
                        </div>
                        <Link href={route('apps.cash-management.payment-requests.index')} className="inline-flex rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-200 dark:bg-sky-900/30 dark:text-sky-300 dark:hover:bg-sky-900/50">
                            Buka Payment Requests
                        </Link>
                    </div>

                    <div className="mt-4 grid grid-cols-2 gap-3 md:grid-cols-4">
                        {summary.map((item) => (
                            <div key={item.label} className="rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-900/40">
                                <p className="text-xs text-gray-500 dark:text-gray-400">{item.label}</p>
                                <p className="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-100">{item.value}</p>
                            </div>
                        ))}
                    </div>
                </Card>

                <Table.Card title="Approval Inbox">
                    <Table>
                        <Table.Thead>
                            <tr>
                                <Table.Th>Request No</Table.Th>
                                <Table.Th>Requester</Table.Th>
                                <Table.Th>Vendor</Table.Th>
                                <Table.Th>Tanggal</Table.Th>
                                <Table.Th>Due Date</Table.Th>
                                <Table.Th>Amount</Table.Th>
                                <Table.Th>Status</Table.Th>
                                <Table.Th>Action</Table.Th>
                            </tr>
                        </Table.Thead>
                        <Table.Tbody>
                            {approvalQueue.map((item) => (
                                <tr key={item.id} className="hover:bg-gray-100 dark:hover:bg-gray-900">
                                    <Table.Td><Link href={`${route('apps.cash-management.payment-requests.index')}?search=${encodeURIComponent(item.request_no)}`} className="text-blue-600 hover:underline dark:text-blue-400">{item.request_no}</Link></Table.Td>
                                    <Table.Td>{item.requester}</Table.Td>
                                    <Table.Td>{item.vendor}</Table.Td>
                                    <Table.Td>{item.request_date}</Table.Td>
                                    <Table.Td>{item.due_date}</Table.Td>
                                    <Table.Td>{item.amount}</Table.Td>
                                    <Table.Td>
                                        <span className={`inline-flex rounded-full px-2 py-1 text-xs font-semibold ${statusClassMap[item.status] ?? 'bg-gray-100 text-gray-600'}`}>
                                            {item.status_label}
                                        </span>
                                    </Table.Td>
                                    <Table.Td>
                                        <div className="space-y-2">
                                            <div className="flex flex-wrap gap-2">
                                                <button
                                                    type="button"
                                                    onClick={() => postAction('apps.cash-management.payment-requests.verify', item.id)}
                                                    disabled={!canVerify(item.status)}
                                                    className="rounded-md border border-amber-200 px-2 py-1 text-xs font-semibold text-amber-700 disabled:cursor-not-allowed disabled:opacity-50 dark:border-amber-700/50 dark:text-amber-300"
                                                >
                                                    Verify
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => postAction('apps.cash-management.payment-requests.approve', item.id)}
                                                    disabled={!canApprove(item.status)}
                                                    className="rounded-md border border-emerald-200 px-2 py-1 text-xs font-semibold text-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 dark:border-emerald-700/50 dark:text-emerald-300"
                                                >
                                                    Approve
                                                </button>
                                            </div>

                                            {canApprove(item.status) && (
                                                <div className="space-y-1">
                                                    <input
                                                        value={reasons[item.id] ?? ''}
                                                        onChange={(event) => updateReason(item.id, event.target.value)}
                                                        placeholder="Alasan reject/revisi"
                                                        className="w-full rounded-md border border-gray-300 bg-white px-2 py-1 text-xs dark:border-gray-700 dark:bg-gray-800"
                                                    />
                                                    <div className="flex flex-wrap gap-2">
                                                        <button
                                                            type="button"
                                                            onClick={() => postAction('apps.cash-management.payment-requests.request-revision', item.id, { reason: reasons[item.id] ?? '' })}
                                                            className="rounded-md border border-orange-200 px-2 py-1 text-xs font-semibold text-orange-700 dark:border-orange-700/50 dark:text-orange-300"
                                                        >
                                                            Request Revision
                                                        </button>
                                                        <button
                                                            type="button"
                                                            onClick={() => postAction('apps.cash-management.payment-requests.reject', item.id, { reason: reasons[item.id] ?? '' })}
                                                            className="rounded-md border border-rose-200 px-2 py-1 text-xs font-semibold text-rose-700 dark:border-rose-700/50 dark:text-rose-300"
                                                        >
                                                            Reject
                                                        </button>
                                                    </div>
                                                </div>
                                            )}

                                            {item.rejected_reason && (
                                                <p className="text-xs text-rose-600 dark:text-rose-400">Reason: {item.rejected_reason}</p>
                                            )}
                                        </div>
                                    </Table.Td>
                                </tr>
                            ))}
                        </Table.Tbody>
                    </Table>
                </Table.Card>
            </div>
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} />;
