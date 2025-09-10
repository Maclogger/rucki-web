import { defineStore } from 'pinia';

// Raw data from response:
interface PhotosResponse {
    photos: Array<{
        id: number,
        file_name: string,
        id_user: number,
        original_name: string,
        mime_type: string,
        size: number,
        readable_size: string,
        created_at: string,
        updated_at: string,
    }>,
}

// Formatted data:
export interface PhotoType {
    id: number,
    file_name: string,
    id_user: number,
    original_name: string,
    mime_type: string,
    readable_size: string,
    created_at: Date,
    updated_at: Date,
    selected: boolean,
}

export enum FetchStatus {
    LOADING,
    LOADED,
    ERROR,
}

export interface PhotosStoreState {
    photos: PhotoType[];
    refreshedAt: Date;
    status: FetchStatus;
    websocketConnection: boolean;
}


export const usePhotosStore = defineStore("photosStore", {
    state: (): PhotosStoreState => {
        return {
            photos: [],
            refreshedAt: new Date(),
            status: FetchStatus.LOADING,
            websocketConnection: false,
        };
    },

    getters: {
        getSelectedCount(state): number {
            return state.photos.filter(p => p.selected).length;
        }
    },

    actions: {
        // setState(newState: PhotosStoreState) {
        //     this.$patch(newState);
        // },
        async refresh() {
            this.photos = [];
            this.status = FetchStatus.LOADING;

            try {
                const response = await window.axios("/get-photos");
                const data: PhotosResponse = response.data;

                if (!data || !data.photos) {
                    console.error("Refreshing photos was not successful!");
                    this.status = FetchStatus.ERROR; // Nastav status na ERROR
                    return;
                }

                if (data.photos.length <= 0) {
                    console.warn("0 photos were received from the server.");
                    this.photos = [];
                    this.refreshedAt = new Date();
                    this.status = FetchStatus.LOADED;
                    return;
                }

                const transformedPhotos: PhotoType[] = data.photos.map(p => ({
                    id: p.id,
                    file_name: p.file_name,
                    id_user: p.id_user,
                    original_name: p.original_name,
                    mime_type: p.mime_type,
                    readable_size: p.readable_size,
                    created_at: new Date(p.created_at),
                    updated_at: new Date(p.updated_at),
                    selected: false,
                }));

                this.photos = transformedPhotos;
                this.refreshedAt = new Date();
                this.status = FetchStatus.LOADED;
            } catch (error) {
                console.error("Error refreshing photos:", error);
                this.status = FetchStatus.ERROR;
            }
        },


        async deleteSinglePhoto(photo: PhotoType) {
            const originalPhotos = this.photos;
            this.photos = this.photos.filter(p => p.id != photo.id); // removing the photo from FrontEnd
            window.axios.post(
                "/delete-single-photo",
                { id: photo.id })
                .then(() => {
                    console.log("Successfuly deleted");
                })
                .catch(() => {
                    console.error("Photo could not be deleted!");
                    this.photos = originalPhotos;
                });
        },

        newPhotoAdded(photo: PhotoType) {
            const existingPhotoIndex = this.photos.findIndex(p => p.id === photo.id);
            const photoExistsAlready = existingPhotoIndex >= 0;
            if (photoExistsAlready) {
                photo.selected = this.photos[existingPhotoIndex].selected;
                this.photos[existingPhotoIndex] = photo;
            } else {
                this.photos.unshift(photo); // adding at the beginning
            }
            this.refreshedAt = new Date();
        },

        photoDeleted(photoId: number) {
            this.photos = this.photos.filter(p => p.id != photoId);
        },
    },

});




/*Illuminate\Database\Eloquent\ModelNotFoundException: No query results for model [App\Models\Photo]. in /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:750
Stack trace:
#0 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(110): Illuminate\Database\Eloquent\Builder->firstOrFail()
#1 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(63): App\Events\PhotoDeleted->restoreModel()
#2 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/SerializesModels.php(97): App\Events\PhotoDeleted->getRestoredPropertyValue()
#3 [internal function]: App\Events\PhotoDeleted->__unserialize()
#4 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(95): unserialize()
#5 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(62): Illuminate\Queue\CallQueuedHandler->getCommand()
#6 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\Queue\CallQueuedHandler->call()
#7 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(444): Illuminate\Queue\Jobs\Job->fire()
#8 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(394): Illuminate\Queue\Worker->process()
#9 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(180): Illuminate\Queue\Worker->runJob()
#10 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\Queue\Worker->daemon()
#11 /var/www/html/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\Queue\Console\WorkCommand->runWorker()
#12 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\Queue\Console\WorkCommand->handle()
#13 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\Container\BoundMethod::{closure:Illuminate\Container\BoundMethod::call():35}()
#14 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\Container\Util::unwrapIfClosure()
#15 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\Container\BoundMethod::callBoundMethod()
#16 /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Container.php(780): Illuminate\Container\BoundMethod::call()
#17 /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\Container\Container->call()
#18 /var/www/html/vendor/symfony/console/Command/Command.php(318): Illuminate\Console\Command->execute()
#19 /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\Component\Console\Command\Command->run()
#20 /var/www/html/vendor/symfony/console/Application.php(1092): Illuminate\Console\Command->run()
#21 /var/www/html/vendor/symfony/console/Application.php(341): Symfony\Component\Console\Application->doRunCommand()
#22 /var/www/html/vendor/symfony/console/Application.php(192): Symfony\Component\Console\Application->doRun()
#23 /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(197): Symfony\Component\Console\Application->run()
#24 /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1234): Illuminate\Foundation\Console\Kernel->handle()
#25 /var/www/html/artisan(16): Illuminate\Foundation\Application->handleCommand()
#26 {main}

*/
