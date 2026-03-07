import Table from '@/Components/Table';
import Widget from '@/Components/Widget';
import AppLayout from '@/Layouts/AppLayout';
import { Head } from '@inertiajs/react';
import { IconAlertTriangle, IconChecklist, IconCoins, IconReportMoney, IconScale } from '@tabler/icons-react';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';
import { Bar } from 'react-chartjs-2';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

export default function Dashboard({ kpis, cashflow, pendingTasks, accountBalances }) {
    const labels = cashflow.map((item) => item.month);

    const chartData = {
        labels,
        datasets: [
            {
                label: 'Cash In (Juta Rupiah)',
                data: cashflow.map((item) => item.in),
                backgroundColor: 'rgba(34, 197, 94, 0.6)',
            },
            {
                label: 'Cash Out (Juta Rupiah)',
                data: cashflow.map((item) => item.out),
                backgroundColor: 'rgba(239, 68, 68, 0.6)',
            },
        ],
    };

    return (
        <>
            <Head title="Cash Management Dashboard" />

            <div className="mb-5">
                <h1 className="text-xl font-semibold text-gray-800 dark:text-gray-100">Cash Management Dashboard</h1>
                <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Monitoring real-time pemasukan, pengeluaran, saldo kas/bank, dan kontrol proses finance berbasis ISO 9001.
                </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                {kpis.map((item) => (
                    <Widget
                        key={item.label}
                        title={item.label}
                        subtitle="KPI Finance"
                        color="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200"
                        icon={<IconReportMoney size={20} strokeWidth={1.5} />}
                        total={item.value}
                    />
                ))}
            </div>

            <div className="grid grid-cols-1 xl:grid-cols-2 gap-4 mt-5 items-start">
                <Table.Card title="Cash In vs Cash Out" icon={<IconCoins size={20} strokeWidth={1.5} />}>
                    <Bar className="min-w-full" data={chartData} />
                </Table.Card>

                <Table.Card title="Outstanding Tasks" icon={<IconChecklist size={20} strokeWidth={1.5} />}>
                    <Table>
                        <Table.Thead>
                            <tr>
                                <Table.Th className="w-14">No</Table.Th>
                                <Table.Th>Task</Table.Th>
                                <Table.Th className="text-right">Jumlah</Table.Th>
                            </tr>
                        </Table.Thead>
                        <Table.Tbody>
                            {pendingTasks.map((task, index) => (
                                <tr key={task.task} className="hover:bg-gray-100 dark:hover:bg-gray-900">
                                    <Table.Td className="text-center">{index + 1}</Table.Td>
                                    <Table.Td>{task.task}</Table.Td>
                                    <Table.Td className="text-right">
                                        <span className="rounded-full px-2.5 py-1 text-xs font-medium border border-amber-500/40 bg-amber-500/10 text-amber-500">
                                            {task.count}
                                        </span>
                                    </Table.Td>
                                </tr>
                            ))}
                        </Table.Tbody>
                    </Table>
                </Table.Card>
            </div>

            <div className="mt-5">
                <Table.Card title="Saldo Kas / Bank" icon={<IconScale size={20} strokeWidth={1.5} />}>
                    <Table>
                        <Table.Thead>
                            <tr>
                                <Table.Th>Akun</Table.Th>
                                <Table.Th>Tipe</Table.Th>
                                <Table.Th className="text-right">Saldo</Table.Th>
                            </tr>
                        </Table.Thead>
                        <Table.Tbody>
                            {accountBalances.map((item) => (
                                <tr key={item.account} className="hover:bg-gray-100 dark:hover:bg-gray-900">
                                    <Table.Td>{item.account}</Table.Td>
                                    <Table.Td>
                                        <span className="rounded-full px-2.5 py-1 text-xs font-medium border border-blue-500/40 bg-blue-500/10 text-blue-500">
                                            {item.type}
                                        </span>
                                    </Table.Td>
                                    <Table.Td className="text-right font-semibold">{item.balance}</Table.Td>
                                </tr>
                            ))}
                        </Table.Tbody>
                    </Table>
                    <div className="mt-4 p-3 rounded-lg border border-rose-500/40 bg-rose-500/10 text-rose-600 text-sm flex items-start gap-2">
                        <IconAlertTriangle size={18} className="mt-0.5" />
                        Alert: 1 rekening kas mendekati saldo minimum. Segera review perencanaan kas harian.
                    </div>
                </Table.Card>
            </div>
        </>
    );
}

Dashboard.layout = (page) => <AppLayout children={page} />;
