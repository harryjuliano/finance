import Card from '@/Components/Card';
import AppLayout from '@/Layouts/AppLayout';
import { Head } from '@inertiajs/react';
import { IconCircleCheck, IconFileDescription } from '@tabler/icons-react';

export default function ModulePage({ title, description, modules, keyControls }) {
    return (
        <>
            <Head title={title} />

            <Card>
                <div className="space-y-4">
                    <div>
                        <h1 className="text-xl font-semibold text-gray-800 dark:text-gray-100">{title}</h1>
                        <p className="text-sm text-gray-600 dark:text-gray-400 mt-1">{description}</p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div className="p-4 rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/40">
                            <h2 className="font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2 mb-3">
                                <IconFileDescription size={18} /> Ruang Lingkup Modul
                            </h2>
                            <ul className="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                {modules.map((module) => (
                                    <li key={module} className="flex items-start gap-2">
                                        <span className="mt-1 h-2 w-2 rounded-full bg-blue-500" />
                                        <span>{module}</span>
                                    </li>
                                ))}
                            </ul>
                        </div>

                        <div className="p-4 rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/40">
                            <h2 className="font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2 mb-3">
                                <IconCircleCheck size={18} /> Kontrol Internal
                            </h2>
                            <ul className="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                {keyControls.map((control) => (
                                    <li key={control} className="flex items-start gap-2">
                                        <span className="mt-1 h-2 w-2 rounded-full bg-emerald-500" />
                                        <span>{control}</span>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </Card>
        </>
    );
}

ModulePage.layout = (page) => <AppLayout children={page} />;
