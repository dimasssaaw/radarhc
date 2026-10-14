<?php

$file = __DIR__.'/resources/views/user/mentoring/show.blade.php';
$content = file_get_contents($file);

// Replace GOALS
$content = preg_replace(
    '/<div x-data=\'{ editing: false, value: \{\{ json_encode\(\$mentoring->grow_goals \?\? ""\)\}\} .*?<\/div>\s*<\/div>/is',
    '<div class="flex flex-col md:flex-row bg-[#d1fae5] rounded-lg overflow-hidden border border-[#a7f3d0]">
        <div class="bg-[#059669] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
            GOALS
        </div>
        <div class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative min-h-[56px] flex items-center">
            @if(!empty($mentoring->grow_goals))
                <span>{{ $mentoring->grow_goals }}</span>
            @else
                <span class="text-gray-400 italic">What do you want?</span>
            @endif
        </div>
    </div>',
    $content
);

// Replace REALITAS
$content = preg_replace(
    '/<div x-data=\'{ editing: false, value: \{\{ json_encode\(\$mentoring->grow_realitas \?\? ""\)\}\} .*?<\/div>\s*<\/div>/is',
    '<div class="flex flex-col md:flex-row bg-[#dbeafe] rounded-lg overflow-hidden border border-[#bfdbfe]">
        <div class="bg-[#3b82f6] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
            REALITAS
        </div>
        <div class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative min-h-[56px] flex items-center">
            @if(!empty($mentoring->grow_realitas))
                <span>{{ $mentoring->grow_realitas }}</span>
            @else
                <span class="text-gray-400 italic">Where are you now?</span>
            @endif
        </div>
    </div>',
    $content
);

// Replace OPTIONS
$content = preg_replace(
    '/<div x-data=\'{ editing: false, value: \{\{ json_encode\(\$mentoring->grow_options \?\? ""\)\}\} .*?<\/div>\s*<\/div>/is',
    '<div class="flex flex-col md:flex-row bg-[#ede9fe] rounded-lg overflow-hidden border border-[#ddd6fe]">
        <div class="bg-[#8b5cf6] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
            OPTIONS
        </div>
        <div class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative min-h-[56px] flex items-center">
            @if(!empty($mentoring->grow_options))
                <span>{{ $mentoring->grow_options }}</span>
            @else
                <span class="text-gray-400 italic">What could you do?</span>
            @endif
        </div>
    </div>',
    $content
);

// Replace WRAP UP
$content = preg_replace(
    '/<div x-data=\'{ editing: false, value: \{\{ json_encode\(\$mentoring->grow_wrap_up \?\? ""\)\}\} .*?<\/div>\s*<\/div>/is',
    '<div class="flex flex-col md:flex-row bg-[#fee2e2] rounded-lg overflow-hidden border border-[#fecaca]">
        <div class="bg-[#ef4444] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
            WRAP UP
        </div>
        <div class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative min-h-[56px] flex items-center">
            @if(!empty($mentoring->grow_wrap_up))
                <span>{{ $mentoring->grow_wrap_up }}</span>
            @else
                <span class="text-gray-400 italic">What will you do?</span>
            @endif
        </div>
    </div>',
    $content
);

// Replace INSIGHT
$content = preg_replace(
    '/<div x-data=\'{ editing: false, value: \{\{ json_encode\(\$mentoring->grow_insight \?\? ""\)\}\} .*?<\/div>\s*<\/div>/is',
    '<div class="flex flex-col md:flex-row bg-[#ffedd5] rounded-lg overflow-hidden border border-[#fed7aa]">
        <div class="bg-[#f97316] text-white font-bold py-4 px-6 md:w-40 flex items-center justify-center tracking-wider shrink-0">
            INSIGHT
        </div>
        <div class="p-4 text-gray-700 flex-1 whitespace-pre-line text-sm text-justify relative min-h-[56px] flex items-center">
            @if(!empty($mentoring->grow_insight))
                <span>{{ $mentoring->grow_insight }}</span>
            @else
                <span class="text-gray-400 italic">Key takeaways and learning insights...</span>
            @endif
        </div>
    </div>',
    $content
);

// also remove the @php block setting $actionUrl
$content = preg_replace('/@php\s*\$actionUrl = .*?@endphp/is', '', $content);

file_put_contents($file, $content);
echo "GROW Model cleaned in Mentoring.\n";
