<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import {computed, reactive, ref, watch} from "vue";
import Datepicker from 'vue3-date-time-picker';
import 'vue3-date-time-picker/dist/main.css'
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {Head} from "@inertiajs/inertia-vue3";
import Multiselect from '@vueform/multiselect'

const  userOptions= ref([
    'Batman',
    'Robin',
    'Joker',
])

const SelectUserOptions = ref([]);

const getUserList = async (query) => {
    console.log(query);
    let option=SelectUserOptions;
    return  await axios.post('listeners',{query:query}).then(
        (res)=>{
            return option.value=res.data.data;
        }
    )
}

const durationInMin = ref([
    {
        'text': '10',
        value: 10
    }, {
        'text': '15',
        value: 15
    }, {
        'text': '30',
        value: 30
    },
    {
        'text': '45',
        value: 45
    },
])

const previewString=()=>{

    let str=[];
        switch (formData.recurrence_type){
            case 0:

                str= [
                    'Every',
                    formData.recurrence_repeat_interval??'',
                    'Day,',
                ];
                break;

                case 1:
                    let weekDay=[]
                    for (let x in formData.recurrence_weekly_days){

                        let foundDay=weekDays.find(y=>y.value==formData.recurrence_weekly_days[x]);

                        if(foundDay!=false && foundDay!= null && foundDay !=undefined)weekDay.push(foundDay.text)
                    }
                    str= [
                        'Every week',
                        weekDay.join()
                        ]


                    break;
        }

        str.push(  (isByOrAfter.value === 'by')?'until '+formData.recurrence_end_date_time??'select end date' :formData.recurrence_end_times+ ' occurrence(s)'??'Select '+ ' occurrence(s)');

        return  str.join(' ')
    };
const durationInHr = ref([
    {
        'text': '0',
        value: 0
    },
    {
        'text': '1',
        value: 1
    }, {
        'text': '2',
        value: 2
    }, {
        'text': '3',
        value: 3
    },
    {
        'text': '5',
        value: 5
    },
    {
        'text': '6',
        value: 6
    },
])
const recurrenceOptions = ref([
    {
        'text': 'Daily',
        value: 0
    },
    {
        'text': 'Weekly',
        value: 1
    }, {
        'text': 'Monthly',
        value: 2
    },
    // {
    //     'text': 'Not Fixed Time',
    //     value: 3
    // },
])
const recurrence = ref();
const date = ref(new Date());
const formData=useForm(
    {
        topic:null,
        date: null,
        agenda: null,
        duration_in_hr: 0,
        duration_in_min: 0,
        type:8,
        recurrence_type:null,
        recurrence_end_date_time:null,
        recurrence_end_times:null,
        recurrence_repeat_interval:null,
        recurrence_monthly_week:null,
        recurrence_monthly_week_day:null,
        recurrence_weekly_days:[],
        selectedUser:[],
       // _token: props.csrf_token
    }
);
const props=defineProps({
        auth:Object,
        errors:Object
});

const hasError=(name)=>{
    return formData.errors.hasOwnProperty(name)
}
const getError=(name)=>{
    return formData.errors[name];
}

const getClassString=(o)=>{
    let str=[];
    for (let x in o){
        if(o[x]){
            str.push(x);
        }


    }
    return str.join(' ');
}

const submitForm = (e) => {
    e.preventDefault();
    console.log("here");
    if(isByOrAfter.value=='after'){
        formData.recurrence_end_date_time=null;
    }else{
        formData.recurrence_end_times=null;
    }
    formData.post('/form',{
     //   preserveScroll:true,
        preserveState:true,
    });



}

const weekDays=[
    { text:'Sun', value:1},
    { text:'Mon', value:2},
    { text:'Tue', value:3},
    { text:'Wed', value:4},
    { text:'Thu', value:5},
    { text:'Fir', value:6},
    { text:'Sat', value:7},
];

const isByOrAfter=ref();
const isRecurring = ref(false);
const minDate = ref(new Date());
const flow = ref(['year','month','calendar','hours','minutes', 'time'])
const format = (date) => {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();



    const dateString = [day, month, year].join('/');
    const timeString =date.toLocaleString('en-US', { hour: 'numeric',minute:'numeric', hour12: true });

    return [dateString,timeString].join(' ');
};

</script>

<template>
    <Head title="Form"/>

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Zoom Meeting Management
            </h2>
        </template>

        <div class="py-12 px-2">
            <div>
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Configuration</h3>
                            <p class="mt-1 text-sm text-gray-600">Meeting Topic, Duration, etc.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form @submit.prevent="submitForm">
                            <div class="shadow sm:rounded-md sm:overflow-hidden">
                                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="col-span-1 ">
                                            <label :class="{'text-red-500':hasError('topic')}" class="block text-sm font-medium text-gray-700"
                                                   > Topic </label>
                                            <div class="mt-1 flex rounded-md shadow-sm">

                                                <input v-model="formData.topic" :class="{'border-red-500':hasError('topic')}"  class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
                                                       placeholder="My Meeting"
                                                       type="text">


                                            </div>
                                            <small v-if="hasError('topic')" class="text-red-500" >
                                                {{getError('topic')}}
                                            </small>
                                        </div>
                                        <div class="col-span-1 ">
                                            <label :class="{'text-red-500':hasError('topic')}" class="block text-sm font-medium text-gray-700"
                                                   > Select User </label>
                                            <Multiselect
                                                v-model="formData.selectedUser"
                                                :delay="0"
                                                :filter-results="false"
                                                :min-chars="1"
                                                :options="getUserList"
                                                :resolve-on-load="false"
                                                :searchable="true"
                                                mode="multiple"
                                                placeholder="Select Listener to invite"

                                            />
                                            <small v-if="hasError('topic')" class="text-red-500" >
                                                {{getError('topic')}}
                                            </small>
                                        </div>
                                    </div>

                                    <div>
                                        <label :class="{'text-red-500':hasError('date')}" class="block text-sm font-medium text-gray-700" >
                                            When </label>
                                        <div class="mt-1">

                                            <Datepicker v-model="formData.date" :enableTimePicker="true" :flow="flow" :format="format"
                                                        :inputClassName="getClassString({'text-danger':hasError('date')})"
                                                        :is24="false"
                                                        :minDate="minDate"
                                                        :previewFormat="format"
                                                        noToday
                                            ></Datepicker>


                                        </div>
                                        <small v-if="hasError('date')" class="text-red-500" >
                                            {{getError('date')}}
                                        </small>
                                    </div>
                                    <div>
                                        <label :class="{'text-red-500':hasError('duration_in_min')||hasError('duration_in_hr')}" class="block text-sm font-medium text-gray-700">
                                            Duration </label>
                                        <div class="mt-1 flex">
                                            <select v-model="formData.duration_in_hr" :class="{'border-red-500':hasError('duration_in_hr')}" class="mt-1  py-2 px-4 w-full border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    >
                                                <option v-for="hr in durationInHr" :value="hr.value">{{ hr.text }}</option>
                                            </select>
                                            <label class="pt-4 mr-3 ml-2 text-sm font-medium text-gray-700">hr</label>
                                            <select v-model="formData.duration_in_min" :class="{'border-red-500':hasError('duration_in_min')}" class="mt-1  py-2 px-4 w-full border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    >
                                                <option v-for="min in durationInMin" :value="min.value">{{ min.text }}</option>
                                            </select>
                                            <label class="pt-4 mr-3 ml-2 text-sm font-medium text-gray-700">min</label>
                                        </div>

                                    </div>


                                    <fieldset>
                                        <div class="mt-4 space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input id="comments" v-model="isRecurring" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                                           name="comments"
                                                           type="checkbox">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label class="font-medium text-gray-700" for="comments">Recurring
                                                        meeting
                                                    <strong v-if="isRecurring" >{{previewString()}}</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-if="isRecurring" class="w-full">
                                            <div class="w-full">
                                                <div class="grid grid-cols-2 gap-6">

                                                    <div class="mt-1">
                                                        <label class="pt-4 mr-3 ml-2 text-sm font-medium text-gray-700">Recurrence</label>
                                                        <select  v-model="formData.recurrence_type"
                                                                 class="mt-1  py-2 px-4 w-full border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                        >
                                                            <option v-for="hr in recurrenceOptions" :value="hr.value">
                                                                {{ hr.text }}
                                                            </option>
                                                        </select>

                                                    </div>
                                                    <div class="mt-1 ">
                                                        <label class="pt-4 mr-3 ml-2 text-sm font-medium text-gray-700">Repeat every</label>
                                                        <select  v-model="formData.recurrence_repeat_interval" class="mt-1  py-2 px-4 w-full border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                        >
                                                            <option v-for="D in 15">{{ D }}</option>
                                                        </select>

                                                    </div>


                                                </div>


                                                <div v-if="formData.recurrence_type==1 || formData.recurrence_type==2" class="grid grid-cols-1">
                                                    <div class="flex mt-4">
                                                        <label class="flex items-center mr-3 ml-2 text-sm font-medium text-gray-700">Occurs on</label>

                                                        <div v-if="formData.recurrence_type==1" class="flex">
                                                            <div class="grid grid-cols-7 gap-3">
                                                                <div v-for="day in weekDays" class="flex items-end">
                                                                    <div class="flex items-center h-5">
                                                                        <input :id="[day.text,'input'].join('_')" v-model="formData.recurrence_weekly_days" :value="day.value" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" type="checkbox">
                                                                    </div>
                                                                    <div class="ml-3 text-sm">
                                                                        <label :for="[day.text,'input'].join('_')" class="font-medium text-gray-700" >{{ day.text }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div v-if="formData.recurrence_type==2" class="flex">
                                                            <div class="flex justify-center items-center">
                                                                <label class="flex items-center mr-3 ml-2 text-sm font-medium text-gray-700">Day</label>
                                                                <select  class="flex items-center ml-2  py-2 px-4 w-full border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                                        >
                                                                    <option v-for="x in 31" :value="x">
                                                                        {{ x }}
                                                                    </option>
                                                                </select>
                                                                <label class="flex items-center mr-3 ml-2 text-sm font-medium text-gray-700">of the month</label>
                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>


                                                <div class="flex pt-4">
                                                    <label class="flex items-center mr-3 ml-2 text-sm font-medium text-gray-700">End Date</label>

                                                    <div class="flex items-center">

                                                        <label class="mr-3 block text-sm font-medium text-gray-700"
                                                               for="push-everything">
                                                            By </label>
                                                        <input v-model="isByOrAfter" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" name="push-notifications" type="radio"
                                                               value="by">
                                                        <label class="ml-3 block text-sm font-medium text-gray-700"
                                                               for="push-everything">
                                                            <Datepicker v-model="formData.recurrence_end_date_time" :class="{'cursor-not-allowed':isByOrAfter=='after'}" :disabled="isByOrAfter=='after'" :enableTimePicker="false"
                                                                        :flow="flow"
                                                                        :format="format"
                                                                        :is24="false"
                                                                        :minDate="minDate"
                                                                        :previewFormat="format"
                                                                        noToday
                                                            ></Datepicker> </label>

                                                    </div>

                                                    <div class="flex items-center pl-2" >

                                                        <label class="mr-3 block text-sm font-medium text-gray-700"
                                                               for="push-everything">
                                                            After </label>
                                                        <input v-model="isByOrAfter" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" name="push-notifications" type="radio"
                                                               value="after">

                                                        <select v-model="formData.recurrence_end_times" :class="{'cursor-not-allowed':isByOrAfter=='by'}" :disabled="isByOrAfter=='by'"
                                                                autocomplete="country-name"
                                                                class="mt-1 ml-2  py-2 px-4 w-full border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                                name="country">
                                                            <option v-for="x in 20" :value="x">
                                                                {{ x }}
                                                            </option>
                                                        </select>

                                                    </div>



                                                </div>


                                            </div>
                                        </div>


                                    </fieldset>


                                </div>


                                <div class="px-4 py-3 bg-gray-50 text-center sm:px-6">
                                    <div class="justify-center w-full py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" type="submit"
                                            @click="submitForm">
                                        Create Meeting
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div aria-hidden="true" class="hidden sm:block">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div v-if="false" class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Personal Information</h3>
                            <p class="mt-1 text-sm text-gray-600">Use a permanent address where you can receive
                                mail.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form >
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700" for="first-name">First
                                                name</label>
                                            <input id="first-name" autocomplete="given-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                   name="first-name"
                                                   type="text">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700" for="last-name">Last
                                                name</label>
                                            <input id="last-name" autocomplete="family-name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                   name="last-name"
                                                   type="text">
                                        </div>

                                        <div class="col-span-6 sm:col-span-4">
                                            <label class="block text-sm font-medium text-gray-700" for="email-address">Email
                                                address</label>
                                            <input id="email-address" autocomplete="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                   name="email-address"
                                                   type="text">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3">
                                            <label class="block text-sm font-medium text-gray-700"
                                                   for="country">Country</label>
                                            <select id="country" autocomplete="country-name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                    name="country">
                                                <option>United States</option>
                                                <option>Canada</option>
                                                <option>Mexico</option>
                                            </select>
                                        </div>

                                        <div class="col-span-6">
                                            <label class="block text-sm font-medium text-gray-700" for="street-address">Street
                                                address</label>
                                            <input id="street-address" autocomplete="street-address" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                   name="street-address"
                                                   type="text">
                                        </div>

                                        <div class="col-span-6 sm:col-span-6 lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700"
                                                   for="city">City</label>
                                            <input id="city" autocomplete="address-level2" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="city"
                                                   type="text">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700" for="region">State /
                                                Province</label>
                                            <input id="region" autocomplete="address-level1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" name="region"
                                                   type="text">
                                        </div>

                                        <div class="col-span-6 sm:col-span-3 lg:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700" for="postal-code">ZIP
                                                / Postal code</label>
                                            <input id="postal-code" autocomplete="postal-code" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                   name="postal-code"
                                                   type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <button class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            type="submit">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div v-if="false" aria-hidden="true" class="hidden sm:block">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div v-if="false" class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Notifications</h3>
                            <p class="mt-1 text-sm text-gray-600">Decide which communications you'd like to receive and
                                how.</p>
                        </div>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form action="#" method="POST">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                    <fieldset>
                                        <legend class="text-base font-medium text-gray-900">By Email</legend>
                                        <div class="mt-4 space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input id="comments" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" name="comments"
                                                           type="checkbox">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label class="font-medium text-gray-700"
                                                           for="comments">Comments</label>
                                                    <p class="text-gray-500">Get notified when someones posts a comment
                                                        on a posting.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input id="candidates" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" name="candidates"
                                                           type="checkbox">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label class="font-medium text-gray-700"
                                                           for="candidates">Candidates</label>
                                                    <p class="text-gray-500">Get notified when a candidate applies for a
                                                        job.</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input id="offers" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" name="offers"
                                                           type="checkbox">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label class="font-medium text-gray-700" for="offers">Offers</label>
                                                    <p class="text-gray-500">Get notified when a candidate accepts or
                                                        rejects an offer.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <div>
                                            <legend class="text-base font-medium text-gray-900">Push Notifications
                                            </legend>
                                            <p class="text-sm text-gray-500">These are delivered via SMS to your mobile
                                                phone.</p>
                                        </div>
                                        <div class="mt-4 space-y-4">
                                            <div class="flex items-center">
                                                <input id="push-everything" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" name="push-notifications"
                                                       type="radio">
                                                <label class="ml-3 block text-sm font-medium text-gray-700"
                                                       for="push-everything">
                                                    Everything </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="push-email" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" name="push-notifications"
                                                       type="radio">
                                                <label class="ml-3 block text-sm font-medium text-gray-700"
                                                       for="push-email"> Same as
                                                    email </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="push-nothing" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" name="push-notifications"
                                                       type="radio">
                                                <label class="ml-3 block text-sm font-medium text-gray-700"
                                                       for="push-nothing"> No push
                                                    notifications </label>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <button class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            type="submit">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
<style >
.text-danger{
    --tw-border-opacity: 1;
    border-color: rgb(239 68 68 / var(--tw-border-opacity))!important;
}

.dp__icon,{

}

</style>

<style src="@vueform/multiselect/themes/default.css"></style>
