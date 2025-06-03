import { type ReactElement } from 'react';

type PaginationProps = {
    currentPage: number;
    lastPage: number;
    onPageChange: (page: number) => void;
};

export default function Pagination({ currentPage, lastPage, onPageChange }: PaginationProps): ReactElement {
    return (
        <div className="flex justify-center gap-6 items-center mt-6">
            <button
                onClick={() => onPageChange(currentPage - 1)}
                disabled={currentPage === 1}
                className="px-3 py-1 sm:px-4 sm:py-2 bg-transparent text-black dark:text-white rounded-md hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black disabled:opacity-50 cursor-pointer"
                aria-label="Previous page"
            >
                Previous
            </button>
            <span className="text-sm text-gray-600 dark:text-gray-300">
                Page {currentPage} of {lastPage}
            </span>
            <button
                onClick={() => onPageChange(currentPage + 1)}
                disabled={currentPage === lastPage}
                className="px-3 py-1 sm:px-4 sm:py-2 bg-transparent text-black dark:text-white rounded-md hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black disabled:opacity-50 cursor-pointer"
                aria-label="Next page"
            >
                Next
            </button>
        </div>
    );
}