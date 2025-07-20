<x-filament::page>
    <x-filament::card>
        <div class="text-center py-12">
            <h1 class="text-4xl font-bold mb-4">Welcome to Our Application</h1>
            <p class="text-gray-600 mb-8">Start building something amazing!</p>
            
            <div class="flex justify-center space-x-4">
                <x-filament::button 
                    tag="a" 
                    color="primary"
                >
                    Get Started
                </x-filament::button>
                
                <x-filament::button 
                    tag="a" 
                    href="/features" 
                    color="secondary"
                >
                    Learn More
                </x-filament::button>
            </div>
        </div>
    </x-filament::card>

    <div class="grid md:grid-cols-3 gap-8 mt-8">
        <x-filament::card>
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Feature One</h2>
                <p class="text-gray-600">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Feature Two</h2>
                <p class="text-gray-600">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
        </x-filament::card>

        <x-filament::card>
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Feature Three</h2>
                <p class="text-gray-600">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
        </x-filament::card>
    </div>
</x-filament::page>