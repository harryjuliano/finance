import AppLayout from '@/Layouts/AppLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { IconCirclePlus, IconDatabaseOff, IconPencilCog, IconPencilCheck, IconTrash } from '@tabler/icons-react';
import Button from '@/Components/Button';
import Input from '@/Components/Input';
import Modal from '@/Components/Modal';
import Pagination from '@/Components/Pagination';
import Search from '@/Components/Search';
import Table from '@/Components/Table';

export default function Index() {
    const { transactions, types, statuses, errors } = usePage().props;

    const { data, setData, transform, post } = useForm({
        id: '',
        reference_no: '',
        transaction_date: '',
        type: types[0],
        description: '',
        amount: '',
        status: statuses[0],
        notes: '',
        isUpdate: false,
        isOpen: false,
    });

    transform((formData) => ({
        ...formData,
        _method: formData.isUpdate ? 'put' : 'post',
    }));

    const resetForm = () => {
        setData({
            id: '',
            reference_no: '',
            transaction_date: '',
            type: types[0],
            description: '',
            amount: '',
            status: statuses[0],
            notes: '',
            isUpdate: false,
            isOpen: false,
        });
    };

    const saveTransaction = (e) => {
        e.preventDefault();

        const url = data.isUpdate
            ? route('apps.cash-management.transactions.update', data.id)
            : route('apps.cash-management.transactions.store');

        post(url, {
            onSuccess: () => resetForm(),
        });
    };

    const formatCurrency = (value) =>
        new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 2,
        }).format(value);

    return (
        <>
            <Head title="Transactions" />

            <div className="mb-2">
                <div className="flex justify-between items-center gap-2">
                    <Button
                        type={'button'}
                        icon={<IconCirclePlus size={20} strokeWidth={1.5} />}
                        variant={'gray'}
                        label={'Tambah Transaksi'}
                        onClick={() => setData('isOpen', true)}
                    />
                    <div className="w-full md:w-4/12">
                        <Search
                            url={route('apps.cash-management.transactions.index')}
                            placeholder="Cari ref no, deskripsi, jenis, atau status..."
                        />
                    </div>
                </div>
            </div>

            <Modal show={data.isOpen} onClose={resetForm} title={data.isUpdate ? 'Ubah Transaksi' : 'Tambah Transaksi'}>
                <form onSubmit={saveTransaction} className="space-y-4">
                    <Input
                        label={'Nomor Referensi'}
                        type={'text'}
                        placeholder={'Masukkan nomor referensi'}
                        value={data.reference_no}
                        onChange={(e) => setData('reference_no', e.target.value)}
                        errors={errors.reference_no}
                    />

                    <Input
                        label={'Tanggal Transaksi'}
                        type={'date'}
                        value={data.transaction_date}
                        onChange={(e) => setData('transaction_date', e.target.value)}
                        errors={errors.transaction_date}
                    />

                    <div className="flex flex-col gap-2">
                        <label className="text-gray-600 text-sm">Jenis</label>
                        <select
                            value={data.type}
                            onChange={(e) => setData('type', e.target.value)}
                            className="w-full px-3 py-1.5 border text-sm rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700"
                        >
                            {types.map((item) => (
                                <option key={item} value={item}>
                                    {item}
                                </option>
                            ))}
                        </select>
                        {errors.type && <small className="text-xs text-red-500">{errors.type}</small>}
                    </div>

                    <Input
                        label={'Deskripsi'}
                        type={'text'}
                        placeholder={'Masukkan deskripsi transaksi'}
                        value={data.description}
                        onChange={(e) => setData('description', e.target.value)}
                        errors={errors.description}
                    />

                    <Input
                        label={'Nominal'}
                        type={'number'}
                        placeholder={'Masukkan nominal transaksi'}
                        value={data.amount}
                        onChange={(e) => setData('amount', e.target.value)}
                        errors={errors.amount}
                    />

                    <div className="flex flex-col gap-2">
                        <label className="text-gray-600 text-sm">Status</label>
                        <select
                            value={data.status}
                            onChange={(e) => setData('status', e.target.value)}
                            className="w-full px-3 py-1.5 border text-sm rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700"
                        >
                            {statuses.map((item) => (
                                <option key={item} value={item}>
                                    {item}
                                </option>
                            ))}
                        </select>
                        {errors.status && <small className="text-xs text-red-500">{errors.status}</small>}
                    </div>

                    <div className="flex flex-col gap-2">
                        <label className="text-gray-600 text-sm">Catatan</label>
                        <textarea
                            value={data.notes}
                            onChange={(e) => setData('notes', e.target.value)}
                            className="w-full px-3 py-1.5 border text-sm rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700"
                            rows={3}
                            placeholder="Catatan tambahan (opsional)"
                        />
                        {errors.notes && <small className="text-xs text-red-500">{errors.notes}</small>}
                    </div>

                    <Button
                        type={'submit'}
                        icon={<IconPencilCheck size={20} strokeWidth={1.5} />}
                        variant={'gray'}
                        label={'Simpan'}
                    />
                </form>
            </Modal>

            <Table.Card title={'Data Transaksi'}>
                <Table>
                    <Table.Thead>
                        <tr>
                            <Table.Th className="w-10">No</Table.Th>
                            <Table.Th>Ref No</Table.Th>
                            <Table.Th>Tanggal</Table.Th>
                            <Table.Th>Jenis</Table.Th>
                            <Table.Th>Deskripsi</Table.Th>
                            <Table.Th>Nominal</Table.Th>
                            <Table.Th>Status</Table.Th>
                            <Table.Th className="w-32"></Table.Th>
                        </tr>
                    </Table.Thead>
                    <Table.Tbody>
                        {transactions.data.length ? (
                            transactions.data.map((item, i) => (
                                <tr className="hover:bg-gray-100 dark:hover:bg-gray-900" key={item.id}>
                                    <Table.Td className="text-center">{++i + (transactions.current_page - 1) * transactions.per_page}</Table.Td>
                                    <Table.Td>{item.reference_no}</Table.Td>
                                    <Table.Td>{item.transaction_date}</Table.Td>
                                    <Table.Td>{item.type}</Table.Td>
                                    <Table.Td>
                                        <div className="space-y-1">
                                            <p>{item.description}</p>
                                            {item.notes && <p className="text-xs text-gray-500 dark:text-gray-400 max-w-md truncate">{item.notes}</p>}
                                        </div>
                                    </Table.Td>
                                    <Table.Td>{formatCurrency(item.amount)}</Table.Td>
                                    <Table.Td>{item.status}</Table.Td>
                                    <Table.Td>
                                        <div className="flex gap-2">
                                            <Button
                                                type={'modal'}
                                                icon={<IconPencilCog size={16} strokeWidth={1.5} />}
                                                variant={'orange'}
                                                onClick={() =>
                                                    setData({
                                                        id: item.id,
                                                        reference_no: item.reference_no,
                                                        transaction_date: item.transaction_date,
                                                        type: item.type,
                                                        description: item.description,
                                                        amount: item.amount,
                                                        status: item.status,
                                                        notes: item.notes ?? '',
                                                        isUpdate: true,
                                                        isOpen: true,
                                                    })
                                                }
                                            />
                                            <Button
                                                type={'delete'}
                                                icon={<IconTrash size={16} strokeWidth={1.5} />}
                                                variant={'rose'}
                                                url={route('apps.cash-management.transactions.destroy', item.id)}
                                            />
                                        </div>
                                    </Table.Td>
                                </tr>
                            ))
                        ) : (
                            <Table.Empty
                                colSpan={8}
                                message={
                                    <>
                                        <div className="flex justify-center items-center text-center mb-2">
                                            <IconDatabaseOff size={24} strokeWidth={1.5} className="text-gray-500 dark:text-white" />
                                        </div>
                                        <span className="text-gray-500">Data transaksi</span>{' '}
                                        <span className="text-rose-500 underline underline-offset-2">tidak ditemukan.</span>
                                    </>
                                }
                            />
                        )}
                    </Table.Tbody>
                </Table>
            </Table.Card>

            {transactions.last_page !== 1 && <Pagination links={transactions.links} />}
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} />;
