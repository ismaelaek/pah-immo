<template>
    <div class="animate-pulse" aria-hidden="true">
        <section class="px-6 pt-4 sm:px-12 lg:px-20 lg:pt-8 xl:px-28">
            <div class="mx-auto max-w-[92rem]">
                <div
                    class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between lg:gap-12"
                >
                    <div class="flex w-full max-w-3xl flex-col">
                        <div class="flex gap-2">
                            <div class="h-7 w-20 rounded-full bg-neutral-200" />
                            <div class="h-7 w-24 rounded-full bg-neutral-200" />
                            <div class="h-7 w-28 rounded-full bg-neutral-100" />
                        </div>
                        <div
                            class="mt-5 h-10 w-full rounded-2xl bg-neutral-200 sm:h-12 lg:h-14"
                        />
                        <div
                            class="mt-3 h-10 w-2/3 rounded-2xl bg-neutral-200 sm:h-12 lg:h-14"
                        />
                        <div
                            class="mt-5 h-5 w-56 rounded-full bg-neutral-100"
                        />
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-48 rounded-2xl bg-neutral-200" />
                        <div class="size-12 rounded-full bg-neutral-100" />
                    </div>
                </div>

                <div class="mt-8 lg:mt-10">
                    <div
                        class="-mx-6 aspect-[4/3] bg-neutral-200 sm:-mx-12 lg:hidden"
                    />
                    <div
                        class="hidden h-[34rem] grid-cols-4 grid-rows-2 gap-2 overflow-hidden rounded-[2rem] lg:grid"
                    >
                        <div class="col-span-2 row-span-2 bg-neutral-200" />
                        <div class="bg-neutral-100" />
                        <div class="bg-neutral-200" />
                        <div class="bg-neutral-200" />
                        <div class="bg-neutral-100" />
                    </div>
                </div>
            </div>
        </section>

        <section class="px-6 py-12 sm:px-12 lg:px-20 lg:py-20 xl:px-28">
            <div
                class="mx-auto grid max-w-[92rem] gap-12 lg:grid-cols-[minmax(0,1fr)_23rem] lg:gap-16 xl:gap-24"
            >
                <div class="flex flex-col gap-14 lg:gap-16">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div
                            v-for="index in 4"
                            :key="index"
                            class="flex flex-col gap-4 rounded-3xl bg-[#F6F6F6] p-5"
                        >
                            <div class="size-11 rounded-2xl bg-neutral-200" />
                            <div class="h-7 w-16 rounded-full bg-neutral-200" />
                            <div class="h-4 w-20 rounded-full bg-neutral-200" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div
                            class="mb-3 h-8 w-48 rounded-full bg-neutral-200"
                        />
                        <div class="h-5 w-full rounded-full bg-neutral-200" />
                        <div class="h-5 w-11/12 rounded-full bg-neutral-200" />
                        <div class="h-5 w-4/5 rounded-full bg-neutral-200" />
                        <div
                            class="mt-4 h-4 w-full rounded-full bg-neutral-100"
                        />
                        <div class="h-4 w-full rounded-full bg-neutral-100" />
                        <div class="h-4 w-3/4 rounded-full bg-neutral-100" />
                    </div>

                    <div>
                        <div class="h-8 w-40 rounded-full bg-neutral-200" />
                        <div
                            class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3"
                        >
                            <div
                                v-for="index in 6"
                                :key="index"
                                class="h-14 rounded-2xl bg-neutral-100"
                            />
                        </div>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <div
                        class="flex flex-col gap-3 rounded-[2rem] p-7 ring-1 ring-neutral-100"
                    >
                        <div class="h-4 w-20 rounded-full bg-neutral-200" />
                        <div class="h-10 w-48 rounded-2xl bg-neutral-200" />
                        <div class="h-4 w-28 rounded-full bg-neutral-100" />
                        <div class="mt-4 h-13 rounded-full bg-neutral-200" />
                        <div class="h-12 rounded-full bg-neutral-100" />
                        <div
                            class="mt-4 h-4 w-3/4 rounded-full bg-neutral-100"
                        />
                        <div class="h-4 w-2/3 rounded-full bg-neutral-100" />
                        <div class="h-4 w-4/5 rounded-full bg-neutral-100" />
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
