<x-app-layout>
	<x-profile-form :dietType="$profile->diet_type" :dietTarget="$profile->diet_target" :mealsCount="$meals_count" :gender="$profile->gender" :birthday="$profile->birthday" :weight="$profile->weight" :height="$profile->height" :targetWeight="$profile->target_weight" :basicActivity="$profile->basic_activity" :sportActivity="$profile->sport_activity" edit="true" />
</x-app-layout>
