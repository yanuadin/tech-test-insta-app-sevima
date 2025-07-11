<div>
    <div class="flex font-sans select-none">
        <!-- Main feed -->
        <main class="flex-1 custom-scrollbar">
            <!-- Stories -->
            <section class="stories-scroll flex space-x-3 overflow-x-auto px-4 py-6 hide-scrollbar">

                <!-- Sample stories -->
                @php
                    $stories = [
                        ['name'=>'aghnianad', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/3bab8546-c40c-46b1-8d92-be38936eb53b.png', 'alt'=>'Close-up portrait of a woman in a blue hijab smiling with a blurred neutral background'],
                        ['name'=>'nisarzz', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/358f1959-1ba1-488d-8507-27eeb8856751.png', 'alt'=>'Man standing on a street with red jacket and casual wear looking sideways'],
                        ['name'=>'mcdonald', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/95618e1a-74b1-428d-a369-d39eddb1f0d1.png', 'alt'=>'McDonald logo in vibrant bright red and yellow colors on black background'],
                        ['name'=>'septiang', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/ecde0a0a-b16b-4a61-a280-86da3b0f4685.png', 'alt'=>'Person wearing a mask and black jacket outdoor'],
                        ['name'=>'elrizqy9', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/574a7934-9b49-4631-bf82-a233de757207.png', 'alt'=>'Man with curly hair sitting in front of neutral background'],
                        ['name'=>'rioyhan', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/5a1dbb9f-5b3c-4138-89be-5ae91072f489.png', 'alt'=>'Man outdoor standing near water with houses behind him'],
                        ['name'=>'riaa.sulis', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/132bb4cb-27f7-4d02-9e03-19cef35d4e36.png', 'alt'=>'Woman selfie with smiling face and natural light'],
                    ];
                @endphp

                @foreach($stories as $story)
                    <div class="story-border p-0.5 rounded-full">
                        <img loading="lazy" src="{{ $story['img'] }}" alt="{{ $story['alt'] }}" class="rounded-full block w-20 h-20 object-cover cursor-pointer border-2 border-orange-600" />
                        <p class="text-xs text-center truncate mt-1 w-20">{{ Str::limit($story['name'],10,'...') }}</p>
                    </div>
                @endforeach
            </section>

            <!-- Posts -->
            <section class="px-4 py-6 space-y-8 max-w-3xl mx-auto">
                @foreach($posts as $post)
                    <article class="bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 rounded-xl shadow-lg overflow-hidden border border-gray-800">
                        <!-- Post header -->
                        <header class="flex items-center p-4 space-x-3">
                            @if($post->user->profile_url)
                                <img loading="lazy" src="{{ $post->user->profile_url }}" alt="Profile Photo" class="w-12 h-12 object-cover rounded-full border-2 border-orange-600" />
                            @else
                                <flux:profile
                                    circle
                                    initials="{{ $post->user->initials() }}"
                                    :chevron="false"
                                />
                            @endif
                            <div>
                                <p class="font-semibold text-white">{{ $post->user->username }}</p>
                                <p class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</p>
                            </div>
                            @if($post->user_id == auth()->user()->id)
                                <flux:dropdown class="ml-auto">
                                    <flux:button icon:trailing="ellipsis-horizontal"></flux:button>
                                    <flux:menu>
                                        <flux:modal.trigger name="delete-post">
                                            <flux:button variant="danger" class="w-full" icon="trash">Delete</flux:button>
                                        </flux:modal.trigger>
                                    </flux:menu>
                                </flux:dropdown>

                                <flux:modal name="delete-post" class="min-w-[22rem]">
                                    <div class="space-y-6">
                                        <div>
                                            <flux:heading size="lg">Delete post?</flux:heading>
                                            <flux:text class="mt-2">
                                                <p>You're about to delete this post.</p>
                                                <p>This action cannot be reversed.</p>
                                            </flux:text>
                                        </div>
                                        <div class="flex gap-2">
                                            <flux:spacer />
                                            <flux:modal.close>
                                                <flux:button variant="ghost">Cancel</flux:button>
                                            </flux:modal.close>
                                            <flux:button type="submit" variant="danger" wire:click="deletePost({{ $post }})">Delete post</flux:button>
                                        </div>
                                    </div>
                                </flux:modal>
                            @endif
                        </header>

                        <div class="post-img-container w-full bg-black relative">
                            <img loading="lazy" src="{{ asset('storage/' . $post->url) }}" alt="Post Image" class="w-full max-h-[600px] object-cover" />
                        </div>

                        <footer class="p-4 space-y-3">
                            <button class="flex items-center space-x-4">
                                <a wire:click="like({{ $post }})" class="cursor-pointer">
                                    <flux:icon.heart variant="{{ $post->likes_count > 0 ? 'solid' : 'outline' }}" class="{{ $post->likes_count > 0 ? 'text-red-700' : '' }}"/>
                                </a>
                                <a href=""><flux:icon.chat-bubble-oval-left /></a>
                                <a href=""><flux:icon.paper-airplane /></a>
                            </button>
                            <p class="whitespace-pre-wrap"><span class="font-semibold">{{ $post->user->username }}</span> {{ $post->caption }}</p>
                            <flux:link class="text-xs" href="#" variant="subtle">View all {{ $post->comments_count }} comment</flux:link>
                            <form wire:submit="addComment({{ $post }})">
                                @csrf
                                <div class="flex">
                                    <flux:input wire:model="comment" class="border-none" placeholder="Add a comment..."/>
                                    <flux:button class="ml-2" variant="primary" type="submit">{{ __('Post') }}</flux:button>
                                </div>
                            </form>

                            <!-- Comment Section -->
                            @foreach($post->comments as $com)
                                <div class="flex">
                                    <flux:profile
                                        circle
                                        initials="{{ $com->user->initials() }}"
                                        :chevron="false"
                                    />
                                    <p class="text-xs truncate ml-1 mt-2 w-full">
                                        <span class="font-semibold mr-1">{{ $com->user->username }}</span>
                                        {{ $com->comment }}
                                        <span class="text-gray-400 text-xs block">
                                            {{ \Carbon\Carbon::parse($com->created_at)->diffForHumans() }} |
                                            <flux:link variant="subtle" wire:click="replyComment({{ $post }}, {{ $com }}, '{{ $com->user->username }}')">Reply</flux:link>
                                            @if($com->user_id == auth()->user()->id)
                                                | <flux:link variant="subtle" wire:click="deleteComment({{ $com }})">Delete</flux:link>
                                            @endif
                                        </span>
                                    </p>
                                </div>
                                @foreach($com->replies as $reply)
                                    <div class="flex ml-3">
                                        <flux:profile
                                            circle
                                            initials="{{ $reply->user->initials() }}"
                                            :chevron="false"
                                        />
                                        <p class="text-xs truncate ml-1 mt-2 w-full">
                                            <span class="font-semibold mr-1">{{ $reply->user->username }}</span>
                                            <span class="block">{{ $reply->comment }}</span>
                                            <span class="text-gray-400 text-xs block">
                                                {{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }} |
                                                <flux:link variant="subtle" wire:click="replyComment({{ $post }}, {{ $com }}, '{{ $reply->user->username }}')">Reply</flux:link>
                                                @if($reply->user_id == auth()->user()->id)
                                                    | <flux:link variant="subtle" wire:click="deleteComment({{ $reply }})">Delete</flux:link>
                                                @endif
                                            </span>
                                        </p>
                                    </div>
                                @endforeach
                            @endforeach
                        </footer>
                    </article>
                @endforeach
            </section>
        </main>

        <!-- Right sidebar -->
        <aside class="hidden lg:flex flex-col w-80 px-6 py-8 overflow-y-auto sticky top-0 h-screen space-y-6 text-sm">
            <!-- Current user info -->
            <section class="flex items-center space-x-4">
                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/ec0643d7-f2e4-4130-ab33-a2759bb3cb82.png" alt="Profile picture of current user wearing a black hoodie and urban setting behind" class="rounded-full w-12 h-12 object-cover border border-gray-700" loading="lazy" onerror="this.onerror=null;this.src='https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/2cab78af-0958-44e4-910e-de157a9612dc.png';" />
                <div>
                    <p class="text-white font-semibold">yanuadi_n</p>
                    <p>Yanu Adi Nugraha</p>
                </div>
                <button class="ml-auto text-blue-600 font-semibold text-sm hover:underline">Switch</button>
            </section>

            <!-- Suggested users -->
            <section>
                <div class="flex justify-between items-center mb-4">
                    <p class="text-gray-500 font-semibold">Suggested for you</p>
                    <button class="text-xs font-semibold hover:underline" aria-label="See all suggested users">See All</button>
                </div>

                <ul class="space-y-4">
                    @php
                        $suggested = [
                            ['username'=>'ilyas.official.1617', 'desc'=>'Followed by hakim.akbaru + 19 more', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/acd67c61-dfdf-4fff-ad1b-fa19218e9513.png', 'alt'=>'Profile of ilyas official with glasses and short beard, studio portrait'],
                            ['username'=>'gedekresnap', 'desc'=>'Followed by ilmo.ccacino + 35 more', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/1c32d406-317e-4d54-a4be-d3200a7b3e17.png', 'alt'=>'Profile of gedekresnap smiling wearing casual blue shirt'],
                            ['username'=>'hkrizki', 'desc'=>'Followed by maharanisalsaa + 7 more', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/5b661c8a-fb20-4e33-9871-7a4203cbb5b8.png', 'alt'=>'Profile of hkrizki portrait with neutral light'],
                            ['username'=>'dimasrakad', 'desc'=>'Followed by ilmo.ccacino + 34 more', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/e70f18dc-4f84-4dba-b8f1-06e29b3aaf2c.png', 'alt'=>'Profile of dimasrakad outdoor casual photo'],
                            ['username'=>'zabilhermann', 'desc'=>'Followed by ilmo.ccacino + 27 more', 'img'=>'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/7e7a22b6-1b2d-463e-bf8b-d1904b7bc4ae.png', 'alt'=>'Profile of zabilhermann smiling bright light photo'],
                        ];
                    @endphp

                    @foreach($suggested as $s)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img loading="lazy" src="{{ $s['img'] }}" alt="{{ $s['alt'] }}" class="w-10 h-10 rounded-full object-cover border border-gray-700" />
                                <div>
                                    <p class="font-semibold text-white text-sm">{{ $s['username'] }}</p>
                                    <p class="text-xs truncate max-w-[160px]" title="{{ $s['desc'] }}">{{ $s['desc'] }}</p>
                                </div>
                            </div>
                            <button class="text-blue-600 font-semibold text-sm hover:underline">Follow</button>
                        </li>
                    @endforeach
                </ul>
            </section>
        </aside>

    </div>
</div>

