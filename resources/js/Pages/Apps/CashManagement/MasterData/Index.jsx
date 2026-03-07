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
    const { masterData, categories, errors } = usePage().props;

    const { data, setData, transform, post } = useForm({
        id: '',
        code: '',
        name: '',
        category: categories[0],
        description: '',
        is_active: true,
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
            code: '',
            name: '',
            category: categories[0],
            description: '',
            is_active: true,
            isUpdate: false,
            isOpen: false,
        });
    };

    const saveMasterData = (e) => {
        e.preventDefault();

        const url = data.isUpdate
            ? route('apps.cash-management.master-data.update', data.id)
            : route('apps.cash-management.master-data.store');

        post(url, {
            onSuccess: () => resetForm(),
        });
    };

    return (
        <>
            <Head title="Master Data" />

            <div className="mb-2">
                <div className="flex justify-between items-center gap-2">
                    <Button
                        type={'button'}
                        icon={<IconCirclePlus size={20} strokeWidth={1.5} />}
                        variant={'gray'}
                        label={'Tambah Master Data'}
                        onClick={() => setData('isOpen', true)}
                    />
                    <div className="w-full md:w-4/12">
                        <Search
                            url={route('apps.cash-management.master-data.index')}
                            placeholder="Cari berdasarkan kode, nama, atau kategori..."
                        />
                    </div>
                </div>
            </div>

            <Modal show={data.isOpen} onClose={resetForm} title={data.isUpdate ? 'Ubah Master Data' : 'Tambah Master Data'}>
                <form onSubmit={saveMasterData} className="space-y-4">
                    <Input
                        label={'Kode'}
                        type={'text'}
                        placeholder={'Masukkan kode master data'}
                        value={data.code}
                        onChange={(e) => setData('code', e.target.value)}
                        errors={errors.code}
                    />

                    <Input
                        label={'Nama'}
                        type={'text'}
                        placeholder={'Masukkan nama master data'}
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        errors={errors.name}
                    />

                    <div className="flex flex-col gap-2">
                        <label className="text-gray-600 text-sm">Kategori</label>
                        <select
                            value={data.category}
                            onChange={(e) => setData('category', e.target.value)}
                            className="w-full px-3 py-1.5 border text-sm rounded-md focus:outline-none focus:ring-0 bg-white text-gray-700 focus:border-gray-200 border-gray-200 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-gray-700 dark:border-gray-800"
                        >
                            {categories.map((category) => (
                                <option key={category} value={category}>
                                    {category}
                                </option>
                            ))}
                        </select>
                        {errors.category && <small className="text-xs text-red-500">{errors.category}</small>}
                    </div>

                    <div className="flex flex-col gap-2">
                        <label className="text-gray-600 text-sm">Deskripsi</label>
                        <textarea
                            value={data.description}
                            onChange={(e) => setData('description', e.target.value)}
                            placeholder="Masukkan deskripsi (opsional)"
                            className="w-full px-3 py-1.5 border text-sm rounded-md focus:outline-none focus:ring-0 bg-white text-gray-700 focus:border-gray-200 border-gray-200 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-gray-700 dark:border-gray-800"
                            rows={3}
                        />
                        {errors.description && <small className="text-xs text-red-500">{errors.description}</small>}
                    </div>

                    <div className="flex flex-col gap-2">
                        <label className="text-gray-600 text-sm">Status</label>
                        <select
                            value={data.is_active ? '1' : '0'}
                            onChange={(e) => setData('is_active', e.target.value === '1')}
                            className="w-full px-3 py-1.5 border text-sm rounded-md focus:outline-none focus:ring-0 bg-white text-gray-700 focus:border-gray-200 border-gray-200 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-gray-700 dark:border-gray-800"
                        >
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        {errors.is_active && <small className="text-xs text-red-500">{errors.is_active}</small>}
                    </div>

                    <Button
                        type={'submit'}
                        icon={<IconPencilCheck size={20} strokeWidth={1.5} />}
                        variant={'gray'}
                        label={'Simpan'}
                    />
                </form>
            </Modal>

            <Table.Card title={'Data Master'}>
                <Table>
                    <Table.Thead>
                        <tr>
                            <Table.Th className="w-10">No</Table.Th>
                            <Table.Th>Kode</Table.Th>
                            <Table.Th>Nama</Table.Th>
                            <Table.Th>Kategori</Table.Th>
                            <Table.Th>Status</Table.Th>
                            <Table.Th className="w-40"></Table.Th>
                        </tr>
                    </Table.Thead>
                    <Table.Tbody>
                        {masterData.data.length ? (
                            masterData.data.map((item, i) => (
                                <tr className="hover:bg-gray-100 dark:hover:bg-gray-900" key={item.id}>
                                    <Table.Td className="text-center">{++i + (masterData.current_page - 1) * masterData.per_page}</Table.Td>
                                    <Table.Td>{item.code}</Table.Td>
                                    <Table.Td>
                                        <div className="space-y-1">
                                            <p>{item.name}</p>
                                            {item.description && (
                                                <p className="text-xs text-gray-500 dark:text-gray-400 max-w-md truncate">{item.description}</p>
                                            )}
                                        </div>
                                    </Table.Td>
                                    <Table.Td>{item.category}</Table.Td>
                                    <Table.Td>
                                        <span
                                            className={`rounded-full px-2.5 py-0.5 text-xs tracking-tight font-medium ${
                                                item.is_active
                                                    ? 'border border-teal-500/40 bg-teal-500/10 text-teal-500'
                                                    : 'border border-rose-500/40 bg-rose-500/10 text-rose-500'
                                            }`}
                                        >
                                            {item.is_active ? 'Aktif' : 'Nonaktif'}
                                        </span>
                                    </Table.Td>
                                    <Table.Td>
                                        <div className="flex gap-2">
                                            <Button
                                                type={'modal'}
                                                icon={<IconPencilCog size={16} strokeWidth={1.5} />}
                                                variant={'orange'}
                                                onClick={() =>
                                                    setData({
                                                        id: item.id,
                                                        code: item.code,
                                                        name: item.name,
                                                        category: item.category,
                                                        description: item.description ?? '',
                                                        is_active: item.is_active,
                                                        isUpdate: true,
                                                        isOpen: true,
                                                    })
                                                }
                                            />
                                            <Button
                                                type={'delete'}
                                                icon={<IconTrash size={16} strokeWidth={1.5} />}
                                                variant={'rose'}
                                                url={route('apps.cash-management.master-data.destroy', item.id)}
                                            />
                                        </div>
                                    </Table.Td>
                                </tr>
                            ))
                        ) : (
                            <Table.Empty
                                colSpan={6}
                                message={
                                    <>
                                        <div className="flex justify-center items-center text-center mb-2">
                                            <IconDatabaseOff size={24} strokeWidth={1.5} className="text-gray-500 dark:text-white" />
                                        </div>
                                        <span className="text-gray-500">Data master data</span>{' '}
                                        <span className="text-rose-500 underline underline-offset-2">tidak ditemukan.</span>
                                    </>
                                }
                            />
                        )}
                    </Table.Tbody>
                </Table>
            </Table.Card>

            {masterData.last_page !== 1 && <Pagination links={masterData.links} />}
        </>
    );
}

Index.layout = (page) => <AppLayout children={page} />;
